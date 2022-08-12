<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Auth\SignInResource;
use App\Models\Client;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Login user and create token using Google access token.
     * 
     * @bodyParam access_token string required Google access token.
     * @bodyParam fcm_token string optional Firebase Cloud Messaging token.
     * @bodyParam device_name string required Device name the user is using.
     * 
     * @apiResource App\Http\Resources\V1\Auth\SignInResource
     * @apiResourceModel App\Models\Client
     * @apiResourceAdditional token=bearer_token_for_authentication
     */
    public function googleSignIn(Request $request)
    {
        return SignInResource::make(Client::first())
            ->additional([
                'token' => 'testtoken123'
            ]);
    }
}
