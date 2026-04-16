<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lead extends Model
{
    protected static function booted()
    {
        static::created(function ($lead) {
            \App\Services\LeadNotificationService::handleNewLead($lead);
        });

        static::updated(function ($lead) {
            if ($lead->isDirty('assigned_to') && $lead->assigned_to) {
                \App\Services\LeadNotificationService::handleLeadAssigned($lead);
            }
        });
    }

    protected $fillable = [
        'enquiry_id','name','email','phone','zip_code','city','budget','address',
        'shades_needed','feedback','windows_count','timeline','status','lead_score',
        'appointment_date','amount','service','deal_details','install_date',
        'advance_amount','payment_status','invoice_number','lost_reason','source','campaign',
        'next_follow_up','reminder_note','assigned_to',
        'automation_stopped', 'last_sms_sent_at', 'sms_sequence_step',
    ];

    protected $casts = [
        'appointment_date'   => 'datetime',
        'next_follow_up'     => 'datetime',
        'install_date'       => 'date',
        'amount'             => 'decimal:2',
        'advance_amount'     => 'decimal:2',
        'automation_stopped' => 'boolean',
        'last_sms_sent_at'   => 'datetime',
    ];

    public function addLog($title, $notes = '', $staffName = 'System'): void
    {
        ActivityLog::log($this->id, $title, $notes, $staffName);
    }

    public function activityLogs() { return $this->hasMany(ActivityLog::class); }
    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }

    public function quotes()
    {
        return $this->hasMany(Quote::class);
    }

    public function followUps()    { return $this->hasMany(FollowUp::class); }
    public function installations(){ return $this->hasMany(Installation::class); }
    public function customer()     { return $this->hasOne(Customer::class); }
    public function enquiry()      { return $this->belongsTo(Enquiry::class); }

    public function calculateScore(): int
    {
        $score = 0;
        
        // Base Stage Score (Funnel Progress)
        $stageScores = [
            'new_lead'               => 10,
            'contacted'              => 20,
            'site_visit_scheduled'   => 35,
            'measurement_done'       => 50,
            'quotation_sent'         => 65,
            'negotiation'            => 75,
            'deal_won'               => 90,
            'installation_pending'   => 95,
            'completed'              => 100,
            'lost'                   => 0
        ];
        $score += $stageScores[$this->status] ?? 10;

        // Info Boosters
        if (!empty($this->email)) $score += 10;
        if (!empty($this->zip_code)) $score += 5;
        
        $windows = (int)($this->windows_count ?? 0);
        if ($windows > 10)     $score += 20;
        elseif ($windows > 5)  $score += 10;
        
        // Cap score at 100
        return min($score, 100);
    }
}
