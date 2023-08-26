<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Plan extends Model
{

    public function getRouteKeyName(){   return 'custom_id';    }
    protected $fillable = [
        'name','description','is_active','months','special_offer_months','prorities','active_at','project_id','custom_id','price'
    ];

    public function project(){  return $this->belongsTo(Project::class);}
}
