<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Table extends Model
{
    protected $fillable = ['name', 'is_active', 'current_session_id'];
    public function sessions() {
        return $this->hasMany(Session::class);
    }

    public function currentSession() {
        return $this->belongsTo(Session::class, 'current_session_id');
    }

}
