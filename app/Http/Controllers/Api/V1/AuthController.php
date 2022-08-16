<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\Auth\SignInResource;
use App\Http\Resources\V1\UserResource;
use App\Models\Client;
use GuzzleHttp\Exception\ClientException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Laravel\Socialite\Facades\Socialite;

/**
 * @group Authentication
 *
 * APIs for managing authentication
 */
class AuthController extends Controller
{
    /**
     * Google SignIn
     *
     * Login user and create token using Google access token.
     *
     * @bodyParam access_token string required Google access token. Use `debug_token` for debugging (only works in debug mode). Example: debug_token
     * @bodyParam fcm_token string optional Firebase Cloud Messaging token. No-example
     * @bodyParam device_name string required Device name the user is using. Example: Scribe
     *
     * @apiResource App\Http\Resources\V1\Auth\SignInResource
     * @apiResourceModel App\Models\Client
     * @apiResourceAdditional token=bearer_token_for_authentication
     */
    public function googleSignIn(Request $request)
    {
        $request->validate([
            'access_token' => 'required|string',
            'fcm_token' => 'string|nullable', // TODO: will be used later for push notification
            'device_name' => 'required',
        ]);

        // for debugging
        if (config('app.debug') && $request->access_token === 'debug_token') {
            $client = Client::first();

            $client->tokens()->delete();

            $token = $client->createToken($request->device_name);

            return SignInResource::make(Client::first())
                ->additional([
                    'token' => $token->plainTextToken,
                ]);
        }

        try {
            $googleUser = Socialite::driver('google')
                ->stateless()
                ->userFromToken($request->access_token);
        } catch (ClientException $e) {
            return response()->json([
                'message' => 'An error occurred while trying to login with Google',
                'error' => $e->getMessage(),
            ], 400);
        }

        $client = Client::where('google_id', $googleUser->id)->first();

        // for testing without access_token
        // $client = Client::first();

        if (! $client) {
            $client = Client::create([
                'google_id' => $googleUser->id,
                'name' => $googleUser->name,
                'email' => $googleUser->email,
            ]);

            $avatar = file_get_contents($googleUser->avatar);

            // store avatar in images/clients/{$client->id}.png
            $client->image_path = Storage::disk('public')->put('images/clients/'.$client->id.'.png', $avatar);

            $client->save();
        }

        $token = $client->createToken($request->device_name);

        return SignInResource::make(Client::first())
            ->additional([
                'token' => $token->plainTextToken,
            ]);
    }

    /**
     * Current User
     *
     * Get the authenticated user.
     *
     * @authenticated
     *
     * @apiResource App\Http\Resources\V1\UserResource
     * @apiResourceModel App\Models\Client
     */
    public function user(Request $request)
    {
        return UserResource::make($request->user());
    }

    /**
     * Logout
     *
     * Revoke current user token, other token can still be used.
     *
     * @authenticated
     *
     * @response {
     *  "message": "Logout Success"
     * }
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Logout Success',
        ]);
    }
}
