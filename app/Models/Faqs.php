<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Faqs extends Model
{
    public function getRouteKeyName(){   return 'custom_id';    }
    protected $fillable = ['custom_id','project_id','question','answer','is_active'];
    public function project(){  return $this->belongsTo(Project::class);    }
}
