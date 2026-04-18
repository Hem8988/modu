<?php

namespace App\Services;

use App\Models\Lead;
use App\Models\Setting;
use App\Models\User;
use App\Models\Appointment;
use App\Models\FollowUp;
use App\Mail\AdminLeadMail;
use App\Mail\UserLeadMail;
use App\Mail\AdminAppointmentMail;
use App\Mail\UserAppointmentMail;
use App\Mail\AdminFollowUpMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Config;

class LeadNotificationService
{
    public static function handleNewLead(Lead $lead)
    {
        $settings = Setting::getAll();
        self::configureMail($settings);

        // Get Admin Contact
        $admin = User::where('role', 'admin')->first();
        $adminEmail = $settings['admin_email'] ?? $admin?->email ?? 'admin@modushade.com';
        $adminPhone = $settings['admin_phone'] ?? $admin?->phone ?? env('ADMIN_PHONE');

        \Log::info('LeadNotification Debug:', [
            'sms_enabled' => $settings['sms_enabled'] ?? 'MISSING',
            'admin_found' => !is_null($admin),
            'admin_phone_from_db' => $admin?->phone,
            'final_admin_phone' => $adminPhone,
            'env_admin_phone' => env('ADMIN_PHONE')
        ]);

        // Send Emails
        try {
            // To Admin
            Mail::to($adminEmail)->send(new AdminLeadMail($lead));
            
            // To User
            if (!empty($lead->email)) {
                Mail::to($lead->email)->send(new UserLeadMail($lead));
            }
        } catch (\Exception $e) {
            \Log::error('Lead Email Notification Error: ' . $e->getMessage());
        }

        // SMS Notifications
        $smsEnabled = ($settings['sms_enabled'] ?? 'off') === 'on';
        if ($smsEnabled) {
            $twilio = app(\App\Services\TwilioService::class);

            // To User (via Sequence / Delayed Job)
            $sequence = json_decode($settings['sms_sequence'] ?? '[]', true);
            if (!empty($sequence) && !empty($lead->phone)) {
                $firstStep = $sequence[0];
                $delayMinutes = self::calculateMinutes($firstStep['delay_value'] ?? 0, $firstStep['delay_unit'] ?? 'minutes');
                \App\Jobs\SendLeadSmsJob::dispatch($lead)->delay(now()->addMinutes($delayMinutes));
            }

            // To Admin (Immediate alert)
            if (!empty($adminPhone)) {
                $adminMsg = "🚨 New Lead Alert: {$lead->name} is interested in {$lead->shades_needed}. Phone: {$lead->phone}";
                try {
                    $twilio->send($adminPhone, $adminMsg);
                } catch (\Exception $e) {
                    \Log::error('Admin SMS Notification Error: ' . $e->getMessage());
                }
            }
        }
    }

    public static function handleNewAppointment(Appointment $appointment)
    {
        $settings = Setting::getAll();
        self::configureMail($settings);
        $lead = $appointment->lead;

        // Admin Info
        $admin = User::where('role', 'admin')->first();
        $adminEmail = $settings['admin_email'] ?? $admin?->email ?? 'admin@modushade.com';
        $adminPhone = $settings['admin_phone'] ?? $admin?->phone ?? env('ADMIN_PHONE');

        try {
            // To Admin & Agent (Email)
            $agent = User::find($lead->assigned_to);
            
            $recipients = array_unique(array_filter([$adminEmail, $agent?->email]));
            if (!empty($recipients)) {
                Mail::to($recipients)->send(new AdminAppointmentMail($appointment));
            }
            
            // To User/Lead (Email)
            if (!empty($lead->email)) {
                Mail::to($lead->email)->send(new UserAppointmentMail($appointment));
            }
        } catch (\Exception $e) {
            \Log::error('Appointment Email Notification Error: ' . $e->getMessage());
        }

        // 3-Way SMS Pulse (Admin, Agent, Client)
        if (($settings['sms_enabled'] ?? 'off') === 'on') {
            $twilio = app(\App\Services\TwilioService::class);
            $msg = "✅ Appointment Confirmed: Site visit for {$lead->name} on " . $appointment->date->format('M d') . " at {$appointment->time}.";
            
            // 1. To Client
            if (!empty($lead->phone)) {
                $twilio->send($lead->phone, $msg);
            }

            // 2. To Admin & 3. To Agent
            $staffPhones = array_unique(array_filter([$adminPhone, $agent?->phone]));
            
            foreach ($staffPhones as $phone) {
                $staffMsg = "📅 Appointment Alert: Site visit for {$lead->name} scheduled on " . $appointment->date->format('M d') . " at {$appointment->time}. Handle with care.";
                try {
                    $twilio->send($phone, $staffMsg);
                } catch (\Exception $e) {
                    \Log::error('Staff Appointment SMS Error: ' . $e->getMessage());
                }
            }
        }
    }

    public static function handleNewFollowUp(FollowUp $followUp)
    {
        $settings = Setting::getAll();
        self::configureMail($settings);
        $lead = $followUp->lead;

        // Admin Info
        $admin = User::where('role', 'admin')->first();
        $adminEmail = $settings['admin_email'] ?? $admin?->email ?? 'admin@modushade.com';
        $adminPhone = $settings['admin_phone'] ?? $admin?->phone ?? env('ADMIN_PHONE');

        try {
            $agent = User::find($lead->assigned_to);
            
            $recipients = array_unique(array_filter([$adminEmail, $agent?->email]));
            if (!empty($recipients)) {
                Mail::to($recipients)->send(new AdminFollowUpMail($followUp));
            }

            // SMS Alert to Staff
            if (($settings['sms_enabled'] ?? 'off') === 'on') {
                $twilio = app(\App\Services\TwilioService::class);
                $staffPhones = array_unique(array_filter([$adminPhone, $agent?->phone]));
                
                foreach ($staffPhones as $phone) {
                    try {
                        $twilio->send($phone, "⏳ Reminder: Follow-up required for {$lead->name} today. Check CRM for notes.");
                    } catch (\Exception $e) {
                        \Log::error('Staff FollowUp SMS Error: ' . $e->getMessage());
                    }
                }
            }
        } catch (\Exception $e) {
            \Log::error('FollowUp Notification Error: ' . $e->getMessage());
        }
    }

    public static function handleLeadAssigned(Lead $lead)
    {
        $settings = Setting::getAll();
        self::configureMail($settings);
        $agent = User::find($lead->assigned_to);

        if (!$agent) return;

        try {
            // Email to Agent
            Mail::to($agent->email)->send(new AdminLeadMail($lead));

            // SMS to Agent
            if (($settings['sms_enabled'] ?? 'off') === 'on' && !empty($agent->phone)) {
                $twilio = app(\App\Services\TwilioService::class);
                $twilio->send($agent->phone, "🎯 Lead Assigned: You have been assigned to {$lead->name}. Please review details and initiate contact.");
            }
        } catch (\Exception $e) {
            \Log::error('Lead Assignment Notification Error: ' . $e->getMessage());
        }
    }

    private static function configureMail($settings)
    {
        if (($settings['mail_mailer'] ?? 'log') === 'smtp') {
            Config::set('mail.mailers.smtp.host', $settings['mail_host'] ?? config('mail.mailers.smtp.host'));
            Config::set('mail.mailers.smtp.port', $settings['mail_port'] ?? config('mail.mailers.smtp.port'));
            Config::set('mail.mailers.smtp.encryption', $settings['mail_encryption'] ?? config('mail.mailers.smtp.encryption'));
            Config::set('mail.mailers.smtp.username', $settings['mail_username'] ?? config('mail.mailers.smtp.username'));
            Config::set('mail.mailers.smtp.password', $settings['mail_password'] ?? config('mail.mailers.smtp.password'));
            Config::set('mail.from.address', $settings['mail_from_address'] ?? config('mail.from_address'));
            Config::set('mail.from.name', $settings['mail_from_name'] ?? config('mail.from.name'));
        }
        Config::set('mail.default', $settings['mail_mailer'] ?? 'log');
    }

    private static function calculateMinutes($val, $unit): int
    {
        return match($unit) {
            'hours' => $val * 60,
            'days' => $val * 1440,
            default => (int)$val
        };
    }
}
