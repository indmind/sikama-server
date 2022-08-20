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
        // {
        //     "id": 1,
        //     "seller_id": 1,
        //     "verified_by": 1,
        //     "category_id": 1,
        //     "name": "Bajigur Mantap",
        //     "description": "Jual bajigur dengan rasa yang sangat mantap",
        //     "is_verified": 1,
        //     "is_active": 1,
        //     "created_at": "2022-08-12T08:00:39.000000Z",
        //     "updated_at": "2022-08-20T04:59:58.000000Z",
        //     "distance": 0.03316245024762715,
        //     "seller": {
        //         "id": 1,
        //         "name": "Tio Irawan",
        //         "email": "tioirawan063@gmail.com",
        //         "google_id": "tio123",
        //         "image_path": "images/clients/fjKrj0xeo3rSmqkPUxsl5RoO31WFfK-metacmFiYml0IGd1eSBpbiAxNiBiaXQucG5n-.png",
        //         "phone": "089523794192",
        //         "created_at": "2022-08-12T07:59:26.000000Z",
        //         "updated_at": "2022-08-12T07:59:26.000000Z",
        //         "position": {
        //             "id": 23,
        //             "client_id": 1,
        //             "latitude": -6.2145,
        //             "longitude": 106.8451,
        //             "created_at": "2022-08-20T04:59:10.000000Z",
        //             "updated_at": "2022-08-20T04:59:10.000000Z"
        //         }
        //     }
        // }
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
        ];
    }
}
