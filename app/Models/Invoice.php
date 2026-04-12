<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    protected $fillable = ['customer_id','invoice_number','total','amount','paid','due','status','due_date','notes'];
    protected $casts = [
        'due_date'   => 'date', 
        'created_at' => 'datetime',
        'total'      => 'decimal:2', 
        'amount'     => 'decimal:2', 
        'paid'       => 'decimal:2', 
        'due'        => 'decimal:2'
    ];
    public function customer() { return $this->belongsTo(Customer::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
