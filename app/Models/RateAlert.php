<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RateAlert extends Model
{
    protected $fillable = [
        'from_currency', 
        'to_currency', 
        'target_rate', 
        'email', 
        'is_triggered'
    ];
}