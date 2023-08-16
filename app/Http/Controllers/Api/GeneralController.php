<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\Api\ProjectResource;
use App\Models\Project;
use Illuminate\Http\Request;

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
}
