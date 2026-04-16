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
