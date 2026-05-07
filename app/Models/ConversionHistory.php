<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ConversionHistory extends Model
{
    protected $fillable = [

        'from_currency',
        'to_currency',
        'amount',
        'converted_amount'

    ];
}