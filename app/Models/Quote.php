<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model {
    public $timestamps = true;
    protected $fillable = [
        'lead_id', 'quote_number', 'subtotal', 'vat_amount', 'freight', 'discount', 
        'total_amount', 'expiry_date', 'status', 'narration', 'comments', 'terms_conditions',
        'client_token', 'signed_at', 'signature_data', 'signature_ip', 'signature_name'
    ];
    protected $casts = [
        'expiry_date'  => 'date', 
        'subtotal'     => 'decimal:2',
        'vat_amount'   => 'decimal:2',
        'freight'      => 'decimal:2',
        'discount'     => 'decimal:2',
        'total_amount' => 'decimal:2',
        'signed_at'    => 'datetime'
    ];

    public function lead()  { return $this->belongsTo(Lead::class); }
    public function items() { return $this->hasMany(QuoteItem::class); }

    public static function generateNumber(): string {
        return 'QT-' . strtoupper(uniqid());
    }

    protected static function booted()
    {
        static::creating(function ($quote) {
            if (!$quote->client_token) {
                $quote->client_token = \Illuminate\Support\Str::random(40);
            }
        });

        static::created(function ($quote) {
            \App\Services\LeadNotificationService::handleNewQuote($quote);
        });
    }
}
