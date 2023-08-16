<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\RegisterRequest;
use App\Http\Resources\Api\UserProfileResource;
use App\Models\Project;
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
                'first_name' => $request['first_name'],
                'last_name' => $request['last_name'],
                'contact_no' => $request['contact_no'],
                'custom_id' => $request['custom_id'],
            ]);

            if ($user) {
                Auth::login($user);
                return (new UserProfileResource($user))
                    ->additional([
                        'meta' => [
                            'message'       =>  trans('api.registered'),
                        ]
                    ]);
            } else { $this->response['meta']['message']  = trans('api.registered_fail'); }
        } catch (\Exception $e) {dd($e); $this->storeErrorLog($e, 'register'); }
        return $this->returnResponse(200);
    }
}
