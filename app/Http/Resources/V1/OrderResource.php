<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_id' => $this->customer_id,
            'vendor_id' => $this->vendor_id,
            'schedule_time' => $this->schedule_time,
            'status' => $this->status,
            'created_at' => $this->created_at,
            'queue_number' => $this->queue_number,
            'customer' => new UserResource($this->whenLoaded('customer')),
            'vendor' => new VendorResource($this->whenLoaded('vendor')),
        ];
    }
}
