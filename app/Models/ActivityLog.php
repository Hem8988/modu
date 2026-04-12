<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class ActivityLog extends Model {
    public $timestamps = true;
    protected $fillable = ['lead_id','action','title','notes','staff_name','user_id'];
    public function lead() { return $this->belongsTo(Lead::class); }

    public static function log($leadId, $title, $notes = '', $staffName = null): void {
        static::create([
            'lead_id'    => $leadId,
            'title'      => $title,
            'notes'      => $notes,
            'staff_name' => $staffName ?? auth()->user()?->name ?? 'System',
            'user_id'    => auth()->id(),
        ]);
    }
}
