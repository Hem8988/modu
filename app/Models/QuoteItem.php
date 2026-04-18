<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class QuoteItem extends Model {
    public $timestamps = false;
    protected $fillable = ['quote_id','product_id','product_name','width','height','quantity','unit_price','vat_percentage','vat_amount','subtotal','options_json'];
    protected $casts = ['options_json' => 'array'];
    public function quote()   { return $this->belongsTo(Quote::class); }
    public function product() { return $this->belongsTo(Product::class); }
}
