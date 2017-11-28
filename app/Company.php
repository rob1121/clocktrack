<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Company extends Model
{
    protected $fillable = ['user_id'];
    public function user() {
        return $this->belongsTo(User::class);
    }
}
