<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Random extends Model
{
    use HasFactory;

    protected $fillable = ["values"];

    public function breakdown(){
        return $this->hasMany('App\Models\Breakdown');
    }
}
