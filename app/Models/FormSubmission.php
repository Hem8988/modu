<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FormSubmission extends Model
{
    protected $fillable = [
        'full_name',
        'email',
        'phone',
        'postal_code',
        'blinds_type',
        'num_windows',
        'project_timeline',
        'special_message',
    ];
}
