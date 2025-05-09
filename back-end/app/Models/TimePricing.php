<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TimePricing extends Model
{
    protected $fillable = [
        'name',
        'price',
        'start_time',
        'end_time',
    ];

    public function sessions()
    {
        return $this->hasMany(Session::class);
    }
}
