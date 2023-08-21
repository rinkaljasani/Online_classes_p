<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserPlanResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id'            =>  $this->custom_id,
            'project'       =>  [
                'id' => $this->project->custom_id,
                'name' => $this->project->name,
            ],
            'device' => new DeviceResource($this->device)
        ];
    }
}
