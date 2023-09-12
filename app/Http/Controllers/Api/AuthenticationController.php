<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\DeviceResource;
use App\Http\Resources\Api\UserProfileResource;
use App\Models\Plan;
use App\Models\Project;
use App\Models\UserDevice;
use App\Models\UserPlan;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Laravel\Ui\Presets\React;

class AuthenticationController extends Controller
{
    public function register(RegisterRequest $request)
    {

        try {
            $request['custom_id'] = getUniqueString('users');

            $project = Project::where('custom_id',$request['project_id'])->first();

            $user = User::updateOrCreate([
                'email' => $request['email'],
                'project_id' => $project->id,
            ],[
                'custom_id' => $request['custom_id'],
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'contact_no' => $request['contact_no'],

            ]);
            UserDevice::where('user_id',$user->id)->update(['is_active'=>'n']);

            $user_device =UserDevice::where(['user_id' => $user->id,'device_id' => $request->device_id])->first();
            if($user_device){

                $user_device->is_active = 'y';
                $user_device->save();
            }else{

                $new_user_device = UserDevice::create([
                    'custom_id' =>getUniqueString('user_devices'),
                    'user_id' => $user->id,
                    'device_id' => $request['device_id'],
                    'device_type' => $request['device_type'],
                    'is_active' => 'y'
                ]);
            }
            // $user_device = UserDevice::updateOrCreate([
            //     'user_id' => $user->id,
            //     'device_id' => $request['device_id'],
            // ],[
            //     'custom_id' => getUniqueString('user_devices'),
            //     'is_active' => 'y'
            // ]);

            if ($user) {
                Auth::login($user);
                return (new UserProfileResource($user))
                    ->additional([
                        'meta' => [
                            'message'       =>  trans('api.registered'),
                            'auth_token'=>$user->createToken(config('utility.auth_token'))->plainTextToken,
                        ]
                    ])->response()->setStatusCode(200);
            } else { $this->response['meta']['message']  = trans('api.registered_fail'); }
        } catch (\Exception $e) {dd($e); $this->storeErrorLog($e, 'register'); }
        return $this->returnResponse(200);
    }

    public function getUserDevice(Request $request){
        try{
            $user = Auth::user();
            $devices = $user->devices;
            if ($devices) {
                return DeviceResource::collection($devices)->additional([
                    'meta' => [
                        'total_devices' => $devices->count(),
                        'message' =>    trans('api.list', ['entity' => __('devices')]),
                        'url'       =>  url()->current(),
                    ]
                ]);
            } else { $this->response['meta']['message']  = trans('api.not_found'); }
        }catch (\Exception $e) { dd($e);$this->storeErrorLog($e, 'register'); }
        return $this->returnResponse(200);
    }

    public function checkDeviceActive(Request $request){
        try{
            $is_active = 0;
            $plan = Plan::whereCustomId($request->plan_id)->first();
            $user_device = UserDevice::whereCustomId($request->device_id)->first();
            $user_plan = UserPlan::where('user_id',auth()->user()->id)->where('plan_id',$plan->id)->where('user_device_id',$user_device->id)->first();

            if($user_plan){
                $is_active = 1;

            }
            $this->response['data']['status']  = $is_active;

        }catch (\Exception $e) { dd($e);$this->storeErrorLog($e,'checkDeviceActive'); }
        return $this->returnResponse(200);
    }
}
