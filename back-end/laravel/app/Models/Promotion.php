<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    protected $fillable = [
        'name',
        'discount_percent',
        'start_date',
        'end_date',
    ];
}
