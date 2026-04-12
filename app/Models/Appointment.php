<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Appointment extends Model {
    public $timestamps = false;
    protected $fillable = ['lead_id','type','date','time','status','notes'];
    protected $casts = ['date' => 'datetime'];
    public function lead() { return $this->belongsTo(Lead::class); }
}
