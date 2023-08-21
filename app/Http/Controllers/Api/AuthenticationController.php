<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\UserProfileResource;
use App\Models\Project;
use App\Models\UserDevice;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

            $user_device = UserDevice::updateOrCreate([
                'user_id' => $user->id,
                'device_id' => $request['device_id'],
            ],[
                'custom_id' => getUniqueString('user_devices')
            ]);

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
}
