<?php
namespace App\Http\Controllers;

use App\Models\Lead;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class TwilioWebhookController extends Controller
{
    /**
     * Handle incoming SMS from Twilio.
     * Rule: Stop automation immediately once the lead replies.
     */
    public function handleReply(Request $request)
    {
        $from = $request->input('From'); // E.164 format
        $body = $request->input('Body');

        Log::info("Twilio SMS Reply received from {$from}: {$body}");

        if (empty($from)) {
            return response('No sender provided', 400);
        }

        $cleanPhone = str_replace(['+', ' ', '-', '(', ')'], '', $from);
        
        Log::info("Searching for lead with phone like: %{$cleanPhone}% or exactly: {$from}");

        $lead = Lead::where('phone', 'LIKE', "%{$cleanPhone}%")
            ->orWhere('phone', $from)
            ->latest()
            ->first();

        if ($lead) {
            Log::info("Lead found! ID: {$lead->id}");
            $lead->update([
                'automation_stopped' => true,
                'status' => 'responded' // Optional: Update status to track lead interaction
            ]);

            Log::info("Automation stopped for Lead ID: {$lead->id} due to SMS reply.");
            
            // Log interaction in activity logs if available
            if (method_exists($lead, 'addLog')) {
                $lead->addLog("Customer replied via SMS: \"{$body}\". Automation stopped.");
            }
        }

        return response('<Response></Response>', 200)
            ->header('Content-Type', 'text/xml');
    }
}
