<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function getRouteKeyName(){   return 'custom_id';    }
    protected $fillable = [
        'name','custom_id'
    ];
    public function users(){
        return $this->hasMany('App\Models\User');
    }
}
