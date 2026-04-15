<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MasterAttribute extends Model
{
    use HasFactory;
    protected $fillable = ['label', 'default_values', 'default_price', 'is_active'];
}
