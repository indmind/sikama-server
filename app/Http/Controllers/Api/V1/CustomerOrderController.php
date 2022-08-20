<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\OrderStatus;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\OrderResource;
use App\Models\Order;
use App\Models\Vendor;
use Illuminate\Http\Request;

/**
 * @group Customer Order
 *
 * @authenticated
 *
 * APIs for customer orders
 */
class CustomerOrderController extends Controller
{
    /**
     * Add order to queue
     *
     * Add order to queue for processing
     *
     * @bodyParam vendor_id int required The id of the vendor. Example: 2
     * @bodyParam schedule_time string optional The time the order should be scheduled in UTC format and should be in the future. Example: 2050-01-01 15:00:00
     *
     * @apiResource App\Http\Resources\V1\OrderResource
     * @apiResourceModel App\Models\Order
     *
     * @response scenario="Vendor not found" status=404 {
     * "message": "The selected vendor id is invalid.",
     * "errors": {
     *      "vendor_id": [
     *          "The selected vendor id is invalid."
     *      ]
     *  }
     * }
     *
     * @response scenario="Already ordered" status=400 {
     *  "message": "You already have an order in progress for this vendor"
     * }
     *
     * @response scenario="Ordered themselves" status=403 {
     *  "message": "You cannot order from yourself"
     * }
     */
    public function order(Request $request)
    {
        $request->validate([
            'vendor_id' => 'required|exists:vendors,id',
            'schedule_time' => 'nullable|date_format:Y-m-d H:i:s|after:now',
        ]);

        $user = $request->user();

        // check if user has already an order in progress (not finished or rejected)
        $order = $user
            ->orders()
            ->where('vendor_id', $request->input('vendor_id'))
            ->where('status', '!=', OrderStatus::Finished)
            ->where('status', '!=', OrderStatus::Rejected)->first();

        if ($order) {
            return response()->json([
                'message' => 'You already have an order in progress for this vendor',
            ], 400);
        }

        // check if vendor seller id is the same as user id
        $vendor = Vendor::findOrFail($request->input('vendor_id'));

        if ($vendor->seller_id == $user->id) {
            return response()->json([
                'message' => 'You cannot order from yourself',
            ], 403);
        }

        $order = $request->user()->orders()->create([
            'vendor_id' => $request->vendor_id,
            'schedule_time' => $request->schedule_time,
        ]);

        $order->refresh();
        $order->load('vendor.seller', 'customer');

        return new OrderResource($order);
    }

    /**
     * Get all orders
     *
     * Get all orders for current user
     *
     * @apiResourceCollection App\Http\Resources\V1\OrderResource
     * @apiResourceModel App\Models\Order
     */
    public function index(Request $request)
    {
        $orders = $request->user()->orders()->with('vendor.seller', 'customer')->get();

        return OrderResource::collection($orders);
    }

    /**
     * Cancel order
     *
     * Cancel order for specific order id
     *
     * @urlParam order_id required The id of the order. Example: 1
     *
     * @response {
     *  "message": "Order cancelled"
     * }
     *
     * @response scenario="Order not found" status=404 {
     *  "message": "The selected order id is invalid."
     * }
     *
     * @response scenario="Order is in progress, cannot cancel" status=400 {
     * "message": "You cannot cancel an order that is in progress"
     * }
     */
    public function cancel(Order $order)
    {
        if ($order->status == OrderStatus::OnProgress) {
            return response()->json([
                'message' => 'You cannot cancel an order that is in progress',
            ], 400);
        }

        $order->delete();

        return response()->json([
            'message' => 'Order cancelled',
        ]);
    }
}
