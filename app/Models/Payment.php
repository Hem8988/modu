<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model {
    public $timestamps = false;
    protected $fillable = ['customer_id','invoice_id','amount','mode','notes','date'];
    protected $casts = ['date' => 'date', 'amount' => 'decimal:2'];
    public function customer() { return $this->belongsTo(Customer::class); }
    public function invoice()  { return $this->belongsTo(Invoice::class); }
}
