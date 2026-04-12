<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class ServiceRequest extends Model {
    protected $fillable = ['customer_id', 'product_type', 'issue_description', 'requested_date', 'assigned_technician', 'status'];
    public function customer() { return $this->belongsTo(Customer::class); }
}
