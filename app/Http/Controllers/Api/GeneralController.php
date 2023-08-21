<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\PlanResource;
use App\Http\Resources\Api\ProjectResource;
use App\Models\Faqs;
use App\Models\Plan;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class GeneralController extends Controller
{
    public function getProject(){
        $projects = Project::get();
        if ($projects) {
            return ProjectResource::collection($projects)->additional([
                'meta' => [
                    'total_transaction' => $projects->count(),
                    'message' =>    trans('api.list', ['entity' => __('Project')]),
                    'url'       =>  url()->current(),
                ]
            ]);
        } else { $this->response['meta']['message']  = trans('api.registered_fail'); }
    }

    public function getPlan(Request $request){


        $project_id = Project::whereCustomId($request->project_id)->first()->id;
        $plans = Plan::where('project_id',$project_id)->get();
        if ($plans) {
            return PlanResource::collection($plans)->additional([
                'meta' => [
                    'total_plan' => $plans->count(),
                    'message' =>    trans('api.list', ['entity' => __('Project')]),
                    'url'       =>  url()->current(),
                ]
            ]);
        } else { $this->response['meta']['message']  = trans('api.registered_fail'); }
    }

    public function getFaq(Request $request){


        $project_id = Project::whereCustomId($request->project_id)->first()->id;
        $faqs = Faqs::where('project_id',$project_id)->get();
        if ($faqs) {
            return ProjectResource::collection($faqs)->additional([
                'meta' => [
                    'total_plan' => $faqs->count(),
                    'message' =>    trans('api.list', ['entity' => __('Project')]),
                    'url'       =>  url()->current(),
                ]
            ]);
        } else { $this->response['meta']['message']  = trans('api.registered_fail'); }
    }


}
