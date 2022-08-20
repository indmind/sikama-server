<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class VendorResource extends JsonResource
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
            'seller_id' => $this->seller_id,
            'verified_by' => $this->verified_by,
            'category_id' => $this->category_id,
            'name' => $this->name,
            'description' => $this->description,
            'is_verified' => $this->is_verified,
            'is_active' => $this->is_active,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'distance' => $this->distance,
            // when loaded
            'seller' => new UserResource($this->whenLoaded('seller')),
            'images' => VendorImagesResource::collection($this->images),
        ];
    }
}
