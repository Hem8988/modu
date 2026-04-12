<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    public $timestamps = false;
    protected $fillable = ['name','category','pricing_type','unit_price','description'];
    protected $casts = ['unit_price' => 'decimal:2'];
    public function quoteItems() { return $this->hasMany(QuoteItem::class); }
}
