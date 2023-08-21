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
            'is_active' => $this->is_active,
            'purchase_at'   => $this->purchase_at,
            'expired_at'   => $this->expiry_at ?? "",
            'project'       => new ProjectResource($this->project),
            'device' => new DeviceResource($this->device),
            'plan' => new PlanResource($this->plan)
        ];
    }
}
