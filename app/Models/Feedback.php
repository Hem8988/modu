<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Feedback extends Model {
    protected $table = 'feedbacks';
    protected $fillable = ['customer_id', 'name', 'phone', 'email', 'project_id', 'type', 'rating', 'comments'];
    public function customer() { return $this->belongsTo(Customer::class); }
}
