<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProjectResource;
use App\Http\Resources\Api\UserPlanResource;
use App\Models\Plan;
use App\Models\Project;
use App\Models\UserDevice;
use App\Models\UserPlan;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PlanController extends Controller
{
    public function getUserActivePlan(Request $request){

        $device = UserDevice::whereCustomId($request->device_id)->first();

        $userplan = UserPlan::whereUserId(auth()->user()->id)->whereUserDeviceId($device->id)->orderBy('created_at','desc')->first();
        if ($userplan) {

            return (new UserPlanResource($userplan))->additional([
                'meta' => [
                    'message' =>    trans('api.list', ['entity' => __('Project')]),
                    'url'       =>  url()->current(),
                ]
            ]);

        } else { $this->response['meta']['message']  = trans('api.registered_fail'); }

    }

    public function addUserPlan(Request $request){
        $user_id = Auth::id();
        $plan = Plan::whereCustomId($request->plan_id)->with('project')->first();
        $device = UserDevice::whereCustomId($request->device_id)->first();
        $userplan = UserPlan::updateOrCreate([
            'user_device_id' => $device->id,
            'plan_id' => $plan->id,
        ],[
            'user_id' => $user_id,
            'custom_id' => getUniqueString('user_plans'),
            'project_id' => $plan->project->id,
            'purchase_at' => Carbon::now(),
            'is_active' => 'y'
        ]);
        if ($userplan) {
            return (new UserPlanResource($userplan))->additional([
                'meta' => [
                    'message' =>    trans('api.list', ['entity' => __('Project')]),
                    'url'       =>  url()->current(),
                ]
            ]);
        } else { $this->response['meta']['message']  = trans('api.registered_fail'); }
        return $userplan;
    }
}
