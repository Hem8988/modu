<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Installation extends Model
{
    protected $fillable = ['lead_id', 'date', 'team', 'notes', 'status'];
    
    public function lead() { return $this->belongsTo(Lead::class); }
}
