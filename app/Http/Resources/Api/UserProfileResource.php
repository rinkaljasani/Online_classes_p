<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class UserProfileResource extends JsonResource
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
            'first_name'    =>  $this->first_name,
            'last_name'     =>  $this->last_name,
            'email'         =>  $this->email,
            'contact_no'    =>  $this->contact_no,
            'project'       =>  [
                'id' => $this->project->custom_id,
                'name' => $this->project->name,
            ],
            'device' => new DeviceResource($this->device)
        ];
        // return parent::toArray($request);
    }
}
