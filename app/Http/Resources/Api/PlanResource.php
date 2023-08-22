<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class PlanResource extends JsonResource
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
            'id' => $this->custom_id,
            'name'  => $this->name,
            'price'  => $this->price,
            'months' => $this->months,
            'special_offer_months' => $this->special_offer_months,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'project'   => new ProjectResource($this->project)
        ];
    }
}
