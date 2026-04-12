<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;

class Enquiry extends Model {
    protected $fillable = ['name','email','phone','city','project','budget','message','source','campaign','status','assigned_to','notes'];
    public function lead() { return $this->hasOne(Lead::class); }
}
