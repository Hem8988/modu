<?php
namespace App\Http\Controllers;

use App\Models\Setting;
use App\Models\ActivityLog;
use App\Models\Lead;
use App\Mail\AdminLeadMail;
use App\Mail\UserLeadMail;
use App\Services\TwilioService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class LeadSubmissionController extends Controller
{
    public function submit(Request $request, TwilioService $twilio)
    {
        $data = $request->validate([
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email:rfc,dns',
            'phone'        => 'required|string|min:10|max:20',
            'postal_code'  => 'required|string|max:10',
            'shades_needed'=> 'required|string',
            'windows_count'=> 'required|string',
            'timeline'     => 'required|string',
            'budget'       => 'required|string',
            'message'      => 'nullable|string',
        ]);

        // Create Enquiry explicitly first, so it shows in Enquiries registry
        $enquiry = \App\Models\Enquiry::create([
            'name'    => $data['full_name'],
            'email'   => $data['email'] ?? null,
            'phone'   => $data['phone'],
            'city'    => $data['postal_code'] ?? null,
            'project' => $data['shades_needed'] ?? null,
            'budget'  => $data['budget'] ?? null,
            'message' => $data['message'] ?? null,
            'source'  => 'Landing Page Form',
            'status'  => 'converted' // instantly converted since it becomes a lead
        ]);

        $lead = Lead::create([
            'enquiry_id'    => $enquiry->id,
            'name'          => $data['full_name'],
            'email'         => $data['email'] ?? null,
            'phone'         => $data['phone'],
            'zip_code'      => $data['postal_code'] ?? null,
            'shades_needed' => $data['shades_needed'] ?? null,
            'windows_count' => $data['windows_count'] ?? 0,
            'timeline'      => $data['timeline'] ?? null,
            'budget'        => $data['budget'] ?? null,
            'feedback'      => $data['message'] ?? null,
            'source'        => 'Landing Page',
            'status'        => 'new_lead', // Match the badge query and UI convention
        ]);
        $lead->lead_score = $lead->calculateScore();
        $lead->save();

        // 📝 Log Activity
        ActivityLog::log($lead->id, 'New Lead Submitted', "Lead from Landing Page: {$lead->name} ({$lead->phone})");

        // 🕒 Fetch dynamic settings
        $settings = Setting::getAll();

        // 📧 Dynamic Mail Configuration
        if (($settings['mail_mailer'] ?? 'log') === 'smtp') {
            Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? config('mail.mailers.smtp.host'));
            Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? config('mail.mailers.smtp.port'));
            Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'));
            Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? config('mail.mailers.smtp.username'));
            Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? config('mail.mailers.smtp.password'));
            Config::set('mail.from.address', $settings['mail_from_address'] ?? config('mail.from.address'));
            Config::set('mail.from.name', $settings['mail_from_name'] ?? config('mail.from.name'));
        }
        Config::set('mail.default', $settings['mail_mailer'] ?? 'log');

        // 📤 Send Emails
        try {
            // To Admin
            $adminEmail = \App\Models\User::where('role', 'admin')->first()?->email ?? 'admin@modushade.com';
            Mail::to($adminEmail)->send(new AdminLeadMail($lead));
            
            // To User
            if ($lead->email) {
                Mail::to($lead->email)->send(new UserLeadMail($lead));
            }
        } catch (\Exception $e) {\Log::error('Lead Email Error: ' . $e->getMessage());}

        // 📱 SMS Notifications
        $smsEnabled = ($settings['sms_enabled'] ?? 'off') === 'on';
        if ($smsEnabled) {
            // To User (First step of sequence or immediate)
            $sequence = json_decode($settings['sms_sequence'] ?? '[]', true);
            if (!empty($sequence)) {
                $firstStep = $sequence[0];
                $delayMinutes = $this->calculateMinutes($firstStep['delay_value'] ?? 0, $firstStep['delay_unit'] ?? 'minutes');
                \App\Jobs\SendLeadSmsJob::dispatch($lead)->delay(now()->addMinutes($delayMinutes));
            }

            // To Admin (Immediate alert)
            $adminPhone = env('ADMIN_PHONE');
            if ($adminPhone) {
                $adminMsg = "🚨 New Lead Alert: {$lead->name} is interested in {$lead->shades_needed}. Phone: {$lead->phone}";
                $twilio->send($adminPhone, $adminMsg);
            }
        }

        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Lead created and notifications sent',
                'lead_id' => $lead->id
            ]);
        }

        return redirect()->route('home')->with('success', 'Thank you! We have received your request.');
    }

    private function calculateMinutes($val, $unit): int
    {
        return match($unit) {
            'hours' => $val * 60,
            'days' => $val * 1440,
            default => (int)$val
        };
    }
}
