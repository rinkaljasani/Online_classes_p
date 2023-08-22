<?php

namespace App\Models;

use App\User;
use Illuminate\Database\Eloquent\Model;

class UserPlan extends Model
{
    protected $fillable = [
        'user_id' ,'plan_id','project_id','purchase_at','user_device_id','custom_id','is_active','expiry_at'
    ];

    public function getRouteKeyName()
    {
        return 'custom_id';
    }

    public function user(){
        return $this->belongsTo(User::class);
    }
    public function plan(){
        return $this->belongsTo(Plan::class);
    }
    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function device(){
        return $this->belongsTo(UserDevice::class,'user_device_id','id');
    }
}
