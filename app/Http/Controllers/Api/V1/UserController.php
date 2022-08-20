<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

/**
 * @group User Management
 *
 * @authenticated
 *
 * APIs for managing user related data like position, etc.
 */
class UserController extends Controller
{
    /**
     * Update user position
     *
     * This will update the current user position, set to null to remove.
     *
     * @bodyParam latitude string optional The latitude of the user. Example: -6.2145
     * @bodyParam longitude string optional The longitude of the user. Example: 106.8451
     *
     * @response {
     *  "message": "User position updated"
     * }
     *
     * @response scenario="called with empty latitude and longitude" {
     *  "message": "User position cleared"
     * }
     */
    public function updatePosition(Request $request)
    {
        $request->validate([
            'latitude' => 'numeric|nullable',
            'longitude' => 'numeric|nullable',
        ]);

        $user = $request->user();

        $user->position()->delete();

        if ($request->latitude && $request->longitude) {
            $user->position()->create([
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
            ]);

            return response()->json([
                'message' => 'User position updated',
            ]);
        }

        return response()->json([
            'message' => 'User position cleared',
        ]);
    }
}
