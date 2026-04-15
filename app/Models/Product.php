<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Product extends Model {
    public $timestamps = false;
    protected $fillable = ['name','category','pricing_type','unit_price','description','attributes'];
    protected $casts = ['unit_price' => 'decimal:2', 'attributes' => 'array'];
    public function quoteItems() { return $this->hasMany(QuoteItem::class); }
}
