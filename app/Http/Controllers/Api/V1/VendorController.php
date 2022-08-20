<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Resources\V1\VendorResource;
use App\Models\Vendor;
use Illuminate\Http\Request;

/**
 * @group Vendor for Client
 *
 * @authenticated
 *
 * APIs for dealing with vendors for 'customer'
 */
class VendorController extends Controller
{
    /**
     * Get nearest vendors
     *
     * Get nearest vendors based on current user position or provided position
     *
     * @queryParam latitude string optional The latitude of the user, will use current user location when null. Example: -6.2145
     * @queryParam longitude string optional The longitude of the user, will use current user location when null. Example: 106.8451
     *
     * @apiResourceCollection App\Http\Resources\V1\VendorResource
     * @apiResourceModel App\Models\Vendor
     *
     * @response scenario="called with empty latitude and longitude and no user position" status=400 {
     *      "message": "Please provide latitude and longitude"
     * }
     */
    public function nearest(Request $request)
    {
        $user = $request->user();

        $latitude = $request->input('latitude', $user->position?->latitude);
        $longitude = $request->input('longitude', $user->position?->longitude);

        if (! $latitude || ! $longitude) {
            return response()->json([
                'message' => 'Please provide latitude and longitude',
            ], 400);
        }

        $vendors = Vendor::getNearestVendors($latitude, $longitude);

        return VendorResource::collection($vendors);
    }

    /**
     * Get vendor detail
     *
     * Get vendor detail by id
     *
     * @urlParam vendor_id required The id of the vendor. Example: 1
     *
     * @queryParam latitude string optional The latitude of the user, will use current user location when null. Example: -6.2145
     * @queryParam longitude string optional The longitude of the user, will use current user location when null. Example: 106.8451
     *
     * @apiResource App\Http\Resources\V1\VendorResource
     * @apiResourceModel App\Models\Vendor
     *
     * @response scenario="called with invalid vendor id" status=4004 {
     *      "message": "Vendor not found"
     * }
     */
    public function show(Request $request, Vendor $vendor)
    {
        $user = $request->user();

        $latitude = $request->input('latitude', $user->position?->latitude);
        $longitude = $request->input('longitude', $user->position?->longitude);

        // this will calculate the distance and load the relationship
        $vendor->distance = $vendor->getDistanceFrom($latitude, $longitude);

        return new VendorResource($vendor);
    }
}
