<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class FollowUp extends Model
{
    protected $fillable = ['lead_id', 'date', 'type', 'notes', 'status'];
    
    public function lead() { return $this->belongsTo(Lead::class); }

    protected static function booted()
    {
        static::created(function ($followUp) {
            \App\Services\LeadNotificationService::handleNewFollowUp($followUp);
        });
    }
}
