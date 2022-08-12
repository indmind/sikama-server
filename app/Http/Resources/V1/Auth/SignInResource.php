<?php

namespace App\Http\Resources\V1\Auth;

use App\Http\Resources\V1\UserResource;
use Illuminate\Http\Resources\Json\JsonResource;

class SignInResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return UserResource::make($this->resource);
    }

    public function with($request)
    {
        return [
            'message' => 'Login Success',
        ];
    }
}
