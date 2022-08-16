<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\UserPositionResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Client;
use Illuminate\Http\Request;

/**
 * @group Position Provider
 *
 * @authenticated
 *
 * APIs for getting other users position.
 */
class UserPositionController extends Controller
{
    /**
     * Get user position
     *
     * Get user position based on user id
     *
     * @urlParam user_id required The ID of the user Example: 1
     *
     * @apiResource App\Http\Resources\V1\UserPositionResource
     * @apiResourceModel App\Models\Position
     */
    public function getUserPosition(Request $request, Client $user)
    {
        return  UserPositionResource::make($user->position)->additional([
            'user' => UserResource::make($user),
        ]);
    }
}
