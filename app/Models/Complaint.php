<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Complaint extends Model {
    protected $fillable = ['customer_id', 'title', 'priority', 'assigned_staff_id', 'description', 'status'];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function assignedStaff() {
        return $this->belongsTo(User::class, 'assigned_staff_id');
    }

    public function resolutions() {
        return $this->hasMany(Resolution::class);
    }
}
