<?php

namespace App\Repositories;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;

class OrderRepository
{
    /**
     * Get all orders
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        if(Auth::user()->is_admin){
            return Order::all();
        }else{
            return Order::where('id', Auth::user()->id)->get();
        }
    }

    /**
     * Get all orders
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getConfirmedOrders()
    {
        if(Auth::user()->is_admin){
            return Order::where('confirmed', 1)->get();
        }else{
            return Order::where('id', Auth::user()->id)->where('confirmed', 1)->get();
        }
    }

    /**
     * Create a new order
     *
     * @param  array  $data
     * @return Order
     */
    public function create(array $data)
    {
        return Order::create([
            'user_id' => $data['user_id'],
            'order_date' => $data['order_date'],
            'order_number' => $data['order_number'],
            'party_name' => $data['party_name'] ?? null,
            'gst_no' => $data['gst_no'] ?? null,
            'party_city' => $data['party_city'] ?? null,
            'party_phone' => $data['party_phone'] ?? null,
            'series' => $data['series'] ?? null,
            'code_no' => $data['code_no'] ?? null,
            'size' => $data['size'] ?? null,
            'auto_rent' => $data['auto_rent'] ?? null,
            'vehicle_rent' => $data['vehicle_rent'] ?? null,
            'transport' => $data['transport'] ?? null,
            'paid_by' => $data['paid_by'] ?? null,
            'total_amount' => $data['total_amount'] ?? null,
            'delivery_from' => $data['delivery_from'] ?? null,
            'package_no' => $data['package_no'] ?? null,
            'purchase_no' => $data['purchase_no'] ?? null,
            'sell_bill_no' => $data['sell_bill_no'] ?? null,
            'bank_name' => $data['bank_name'] ?? null,
            'date' => $data['date'] ?? null,
            'cash_received_by' => $data['cash_received_by'] ?? null,
        ]);
    }

    /**
     * Find a order by ID
     *
     * @param  int  $id
     * @return Order|null
     */
    public function find($id)
    {
        return Order::find($id);
    }

    public function findByUserAndId($userId, $id)
    {
        return Order::where('id', $id)
            ->where('user_id', $userId)
            ->first();
    }

    /**
     * Update a order by ID
     *
     * @param  int  $id
     * @param  array  $data
     * @return Order
     */
    public function update($id, array $data)
    {
        $order = Auth::user()->is_admin
            ? $this->find($id)
            : $this->findByUserAndId(Auth::user()->id, $id);

        if (!$order) {
            return null;
        }

        // Update and return the updated order
        $order->update($data);
        return $order;
    }

    /**
     * Delete a order by ID
     *
     * @param  int  $id
     * @return bool|null
     */
    public function delete($id)
    {
        $authUser = Auth::user();

        // Fetch the order with role-specific logic
        $order = $authUser->is_admin
            ? $this->find($id)
            : $this->findByUserAndId($authUser->id, $id);

        if (!$order) {
            return false;
        }

        return $order->delete();
    }

}
