<?php

namespace App;

use App\Models\UserDevice;
use App\Models\Project;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens,Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    public function getRouteKeyName()
    {
        return 'custom_id';
    }

    protected $fillable = [
        'custom_id', 'social_id','first_name', 'last_name', 'email', 'contact_no', 'profile_photo', 'password', 'device_id','device_type','project_id',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];
    // relationships

    public function project(){
        return $this->belongsTo(Project::class);
    }
    public function devices(){  return $this->hasMany(UserDevice::class);   }
    public function active_devices(){  return $this->hasMany(UserDevice::class)->where('is_active','y');   }
    public function device(){  return $this->hasOne(UserDevice::class)->where('is_active','y')->latest();   }
}
