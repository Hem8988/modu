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
            'lead_id'      => 'nullable|integer|exists:leads,id',
            'full_name'    => 'required|string|max:255',
            'email'        => 'required|email:rfc,dns',
            'phone'        => 'required|string|min:10|max:20',
            'postal_code'  => 'required|string|max:10',
            'shades_needed'=> 'nullable|string',
            'windows_count'=> 'nullable|string',
            'timeline'     => 'nullable|string',
            'budget'       => 'nullable|string',
            'message'      => 'nullable|string',
        ]);

        if (isset($data['lead_id'])) {
            $lead = Lead::findOrFail($data['lead_id']);
            $lead->update([
                'name'          => $data['full_name'],
                'email'         => $data['email'] ?? null,
                'phone'         => $data['phone'],
                'zip_code'      => $data['postal_code'] ?? null,
                'shades_needed' => $data['shades_needed'] ?? $lead->shades_needed,
                'windows_count' => $data['windows_count'] ?? $lead->windows_count,
                'timeline'      => $data['timeline'] ?? $lead->timeline,
                'budget'        => $data['budget'] ?? $lead->budget,
                'feedback'      => $data['message'] ?? $lead->feedback,
            ]);
            $lead->lead_score = $lead->calculateScore();
            $lead->save();

            if ($lead->enquiry_id) {
                $enquiry = \App\Models\Enquiry::find($lead->enquiry_id);
                if ($enquiry) {
                    $enquiry->update([
                        'name'    => $data['full_name'],
                        'email'   => $data['email'] ?? null,
                        'phone'   => $data['phone'],
                        'city'    => $data['postal_code'] ?? null,
                        'project' => $data['shades_needed'] ?? $enquiry->project,
                        'budget'  => $data['budget'] ?? $enquiry->budget,
                        'message' => $data['message'] ?? $enquiry->message,
                    ]);
                }
            }
            ActivityLog::log($lead->id, 'Lead Updated', "Lead {$lead->name} provided additional info.");
            $messageStr = 'Lead updated successfully';

        } else {
            $enquiry = \App\Models\Enquiry::create([
                'name'    => $data['full_name'],
                'email'   => $data['email'] ?? null,
                'phone'   => $data['phone'],
                'city'    => $data['postal_code'] ?? null,
                'project' => $data['shades_needed'] ?? null,
                'budget'  => $data['budget'] ?? null,
                'message' => $data['message'] ?? null,
                'source'  => 'Landing Page Form',
                'status'  => 'converted'
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
                'status'        => 'new_lead',
            ]);
            $lead->lead_score = $lead->calculateScore();
            $lead->save();
            ActivityLog::log($lead->id, 'New Lead Submitted', "Lead from Landing Page: {$lead->name} ({$lead->phone})");
            $messageStr = 'Lead created and notifications sent';
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
