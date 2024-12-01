<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use App\Repositories\OrderRepository;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    protected $userRepository;

    protected $orderRepository;

    public function __construct(OrderRepository $orderRepository) {
        $this->orderRepository = $orderRepository;
    }

    /**
     * Display a listing of the orders.
     *
     * @return JsonResponse
     */
    public function index()
    {
        $orders = $this->orderRepository->all();

        return response()->json([
            'success' => true,
            'data'    => [
                'totalRecords' => count($orders),
                'orders' => $orders
            ],
            'message' => 'Order retrieved successfully.',
        ], 200);
    }

    /**
     * Display a confirmed listing of the orders.
     *
     * @return JsonResponse
     */
    public function confirmedOrders()
    {
        $orders = $this->orderRepository->getConfirmedOrders();

        return response()->json([
            'success' => true,
            'data'    => [
                'totalRecords' => count($orders),
                'orders' => $orders
            ],
            'message' => 'Order retrieved successfully.',
        ], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'order_date' => 'required|date',
            'order_number' => 'required|string|unique:orders,order_number',
            'party_name' => 'nullable|string|max:255',
            'gst_no' => 'nullable|string',
            'party_city' => 'nullable|string|max:255',
            'party_phone' => 'nullable|string|max:15',
            'series' => 'nullable|string|max:255',
            'code_no' => 'nullable|string|max:255',
            'size' => 'nullable|string|regex:/^\d+(\.\d+)?\s*x\s*\d+(\.\d+)?$/',
            'auto_rent' => 'nullable|numeric|min:0',
            'vehicle_rent' => 'nullable|numeric|min:0',
            'transport' => 'nullable|string|max:255',
            'paid_by' => 'nullable|string|max:255',
            'total_amount' => 'nullable|numeric|min:0',
            'delivery_from' => 'nullable|string|max:255',
            'package_no' => 'nullable|string|max:255',
            'purchase_no' => 'nullable|string|max:255',
            'sell_bill_no' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'cash_received_by' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        // Use repository to create user
        $user = $this->orderRepository->create($request->all());

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => 'Order created successfully.',
        ], 200);
    }

    /**
     * Display the specified user.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $authUser = Auth::user();

        // Admin can access any order, while non-admin can only access their own orders
        if (!$authUser->is_admin) {
            $order = $this->orderRepository->findByUserAndId($authUser->id, $id);
        } else {
            $order = $this->orderRepository->find($id);
        }

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or unauthorized access.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $order,
            'message' => 'Order retrieved successfully.',
        ], 200);
    }



    /**
     * Update the specified user in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function update(Request $request, $id): JsonResponse
    {
        $authUser = Auth::user();

        // Check if the order exists and restrict non-admin users
        $order = $authUser->is_admin
            ? $this->orderRepository->find($id)
            : $this->orderRepository->findByUserAndId($authUser->id, $id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or unauthorized access.',
            ], 404);
        }

        // Validate the update data
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'order_date' => 'nullable|date',
            'order_number' => 'required|string|unique:orders,order_number,' . $id,
            'party_name' => 'nullable|string|max:255',
            'gst_no' => 'nullable|string',
            'party_city' => 'nullable|string|max:255',
            'party_phone' => 'nullable|string|max:15',
            'series' => 'nullable|string|max:255',
            'code_no' => 'nullable|string|max:255',
            'size' => 'nullable|string|regex:/^\d+(\.\d+)?\s*x\s*\d+(\.\d+)?$/', // Format: ft x ft
            'auto_rent' => 'nullable|numeric|min:0',
            'vehicle_rent' => 'nullable|numeric|min:0',
            'transport' => 'nullable|string|max:255',
            'paid_by' => 'nullable|string|max:255',
            'total_amount' => 'nullable|numeric|min:0',
            'delivery_from' => 'nullable|string|max:255',
            'package_no' => 'nullable|string|max:255',
            'purchase_no' => 'nullable|string|max:255',
            'sell_bill_no' => 'nullable|string|max:255',
            'bank_name' => 'nullable|string|max:255',
            'date' => 'nullable|date',
            'cash_received_by' => 'nullable|string|max:255',
        ]);

        // Return validation errors if present
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        // Update the order
        $updatedOrder = $this->orderRepository->update($id, $validator->validated());

        return response()->json([
            'success' => true,
            'data' => $updatedOrder,
            'message' => 'Order updated successfully.',
        ], 200);
    }


    /**
     * Update the order status in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return JsonResponse
     */
    public function confirmOrder(Request $request, $id): JsonResponse
    {
        // Validate the `confirmed` field
        $validator = Validator::make($request->all(), [
            'confirmed' => 'required|in:0,1',
        ]);

        // Return validation errors, if any
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors(),
            ], 422);
        }

        // Check if the authenticated user is an admin
        if (!Auth::user() || !Auth::user()->is_admin) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access. Only admin can confirm the order.',
            ], 403);
        }

        // Find the order using the repository
        $order = $this->orderRepository->find($id);

        // Check if the order exists
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found.',
            ], 404);
        }

        // Update the `confirmed` field
        $order->confirmed = $request->input('confirmed');
        $order->save();

        return response()->json([
            'success' => true,
            'message' => 'Order confirmation status updated successfully.',
            'data' => $order,
        ], 200);
    }


    /**
     * Remove the specified order from storage.
     *
     * @param  int  $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $authUser = Auth::user();

        // Check if the order exists and restrict non-admin users
        $order = $authUser->is_admin
            ? $this->orderRepository->find($id)
            : $this->orderRepository->findByUserAndId($authUser->id, $id);

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found or unauthorized access.',
            ], 404);
        }

        // Attempt to delete the order
        $deleted = $this->orderRepository->delete($id);

        if ($deleted) {
            return response()->json([
                'success' => true,
                'message' => 'Order deleted successfully.',
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Failed to delete the order.',
            ], 500);
        }
    }

}
