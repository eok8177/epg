<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    protected $guarded = ['id'];

    public function chanel()
    {
        return $this->belongsTo(Chanel::class);
    }
}