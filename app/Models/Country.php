<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    protected $fillable = ['code', 'name', 'phonecode'];

    public function states()
    {
        return $this->hasMany('App\Models\State');
    }
}
