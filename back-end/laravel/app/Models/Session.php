<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    protected $fillable = [
        'start_time',
        'end_time',
        'time_pricing_id',
        'table_id',
        'promotion_id',
    ];
    public function table() {
        return $this->belongsTo(Table::class);
    }

    public function promotion() {
        return $this->belongsTo(Promotion::class);
    }

}
