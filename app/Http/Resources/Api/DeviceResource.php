<?php

namespace App\Http\Resources\Api;

use Illuminate\Http\Resources\Json\JsonResource;

class DeviceResource extends JsonResource
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
            'device_id' => $this->device_id,
            'device_type' => $this->device_type,
            'is_active' => $this->is_active,
        ];

    }
}
