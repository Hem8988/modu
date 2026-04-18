<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model {
    protected $fillable = [
        'customer_id', 'invoice_number', 'subtotal', 'vat_amount', 'discount', 
        'freight', 'total', 'amount', 'paid', 'due', 'status', 'due_date', 'notes'
    ];
    protected $casts = [
        'due_date'   => 'date', 
        'created_at' => 'datetime',
        'subtotal'   => 'decimal:2',
        'vat_amount' => 'decimal:2',
        'discount'   => 'decimal:2',
        'freight'    => 'decimal:2',
        'total'      => 'decimal:2', 
        'amount'     => 'decimal:2', 
        'paid'       => 'decimal:2', 
        'due'        => 'decimal:2'
    ];
    public function customer() { return $this->belongsTo(Customer::class); }
    public function payments() { return $this->hasMany(Payment::class); }
}
