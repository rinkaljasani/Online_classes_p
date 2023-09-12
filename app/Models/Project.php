<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    public function getRouteKeyName(){   return 'custom_id';    }
    protected $fillable = [
        'name','custom_id'
    ];
    public function users(){
        return $this->hasMany(User::class,'project_id','id');
    }
    public function plans(){
        return $this->hasMany(Plan::class,'project_id','id')->orderBy('prorities');
    }
}
