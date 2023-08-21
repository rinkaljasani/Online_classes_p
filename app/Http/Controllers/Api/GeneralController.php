<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\FaqResource;
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
                    'total_projects' => $projects->count(),
                    'message' =>    trans('api.list', ['entity' => __('Project')]),
                    'url'       =>  url()->current(),
                ]
            ]);
        } else { $this->response['meta']['message']  = trans('api.not_found'); }
    }

    public function getPlan(Request $request){


        $project_id = Project::whereCustomId($request->project_id)->first()->id;
        $plans = Plan::where('project_id',$project_id)->get();
        if ($plans) {
            return PlanResource::collection($plans)->additional([
                'meta' => [
                    'total_plan' => $plans->count(),
                    'message' =>    trans('api.list', ['entity' => __('Plan')]),
                    'url'       =>  url()->current(),
                ]
            ]);
        } else { $this->response['meta']['message']  = trans('api.not_found'); }
    }

    public function getFaq(Request $request){


        $project_id = Project::whereCustomId($request->project_id)->first()->id;
        $faqs = Faqs::where('project_id',$project_id)->get();
        if ($faqs) {
            return FaqResource::collection($faqs)->additional([
                'meta' => [
                    'total_faqs' => $faqs->count(),
                    'message' =>    trans('api.list', ['entity' => __('FAQs')]),
                    'url'       =>  url()->current(),
                ]
            ]);
        } else { $this->response['meta']['message']  = trans('api.not_found'); }
    }


}
