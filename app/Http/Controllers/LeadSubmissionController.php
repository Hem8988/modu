<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use App\Models\Enquiry;
use App\Services\TwilioService;
use Illuminate\Http\Request;

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

        $lead = Lead::create([
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
            'status'        => 'new',
        ]);
        $lead->lead_score = $lead->calculateScore();
        $lead->save();

        // 🕒 Fetch dynamic SMS automation settings
        $settings = \App\Models\Setting::getAll();
        $smsEnabled = ($settings['sms_enabled'] ?? 'off') === 'on';
        $sequence = json_decode($settings['sms_sequence'] ?? '[]', true);

        if ($smsEnabled && !empty($sequence)) {
            $firstStep = $sequence[0];
            $delayValue = (int)($firstStep['delay_value'] ?? 0);
            $delayUnit = $firstStep['delay_unit'] ?? 'minutes';

            $delayMinutes = match ($delayUnit) {
                'hours' => $delayValue * 60,
                'days' => $delayValue * 1440,
                default => $delayValue,
            };

            \App\Jobs\SendLeadSmsJob::dispatch($lead)->delay(now()->addMinutes($delayMinutes));
        }

        if ($request->expectsJson() || $request->is('api/*') || $request->ajax()) {
            return response()->json([
                'status' => 'success',
                'message' => 'Lead created and automation triggered',
                'lead_id' => $lead->id
            ]);
        }

        return redirect()->route('welcome')->with('success', 'Thank you! We have received your request.');
    }
}
