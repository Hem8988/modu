<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Resolution extends Model {
    protected $fillable = ['complaint_id', 'staff_id', 'action_taken', 'notes', 'resolution_date', 'status'];
    public function complaint() { return $this->belongsTo(Complaint::class); }
    public function staff() { return $this->belongsTo(User::class, 'staff_id'); }
}
