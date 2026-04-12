<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model {
    protected $fillable = ['lead_id','name','phone','email','address','project','source','converted_date'];
    public function lead()     { return $this->belongsTo(Lead::class); }
    public function invoices() { return $this->hasMany(Invoice::class); }
    public function payments() { return $this->hasMany(Payment::class); }
    public function complaints() { return $this->hasMany(Complaint::class); }
}
