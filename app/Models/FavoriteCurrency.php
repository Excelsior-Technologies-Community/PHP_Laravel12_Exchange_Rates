<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FavoriteCurrency extends Model
{
    protected $fillable = ['currency_code', 'currency_name', 'sort_order'];
}