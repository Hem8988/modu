<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;

class SendLeadSmsJob implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new job instance.
     */
    public function __construct(public \App\Models\Lead $lead)
    {
    }

    /**
     * Execute the job.
     */
    public function handle(\App\Services\TwilioService $twilio): void
    {
        // Refresh lead from DB to check for latest status
        $this->lead->refresh();

        // ⚠️ Rule: Stop automation immediately once the lead replies
        if ($this->lead->automation_stopped) {
            return;
        }

        // 🕒 Fetch dynamic SMS sequence
        $settings = \App\Models\Setting::getAll();
        $sequenceJson = $settings['sms_sequence'] ?? '[]';
        $sequence = json_decode($sequenceJson, true);
        
        // If legacy single-template setting exists and sequence is empty, we could fall back, 
        // but the plan is to move to sequence.
        if (empty($sequence)) {
             return;
        }

        $currentStepIdx = $this->lead->sms_sequence_step;

        // If we've finished the sequence, stop.
        if (!isset($sequence[$currentStepIdx])) {
            return;
        }

        $step = $sequence[$currentStepIdx];
        $template = $step['template'] ?? '';

        if (empty($template)) {
            return;
        }

        // Replacement logic for tags
        $productType = $this->lead->shades_needed ?? 'shades';
        $windowsCount = $this->lead->windows_count ?? 0;

        $message = str_replace(
            ['{{name}}', '{{product_type}}', '{{windows_count}}'],
            [$this->lead->name, $productType, $windowsCount],
            $template
        );

        // Send SMS
        $sent = $twilio->send($this->lead->phone, $message);

        if ($sent) {
            $nextStepIdx = $currentStepIdx + 1;
            $this->lead->update([
                'last_sms_sent_at' => now(),
                'sms_sequence_step' => $nextStepIdx,
            ]);

            // Dispatch next step if it exists
            if (isset($sequence[$nextStepIdx])) {
                $nextStep = $sequence[$nextStepIdx];
                $delayMinutes = $this->calculateDelayMinutes($nextStep['delay_value'] ?? 0, $nextStep['delay_unit'] ?? 'minutes');
                
                // Recursive dispatch
                self::dispatch($this->lead)->delay(now()->addMinutes($delayMinutes));
            }
        }
    }

    /**
     * Helper to convert various time units to minutes for Laravel delay.
     */
    private function calculateDelayMinutes($value, $unit): int
    {
        $value = (int)$value;
        return match ($unit) {
            'hours' => $value * 60,
            'days' => $value * 1440,
            default => $value, // minutes
        };
    }
}
