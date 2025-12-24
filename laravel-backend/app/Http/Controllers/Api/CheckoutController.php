<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CheckoutController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'name'          => 'required|string|max:255',
            'email'         => 'required|email',
            'phone'         => 'required|string|max:20',
            'address'       => 'required|string|max:255',
            'city'          => 'required|string|max:100',
            'state'         => 'required|string|max:100',
            'postal_code'   => 'required|string|max:10',
            'country'       => 'required|string|max:100',
            'cart'          => 'required|array',
            'cart.*.id'     => 'required|integer',
            'cart.*.price'  => 'required|numeric',
            'cart.*.quantity' => 'required|integer|min:1',
            'payment_method' => 'required|string', // COD or Stripe
            'transaction_id' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // ✅ 1. Create or find customer
            $customer = Customer::firstOrCreate(
                ['email' => $request->email],
                [
                    'name'        => $request->name,
                    'phone'       => $request->phone,
                    'address'     => $request->address,
                    'city'        => $request->city,
                    'state'       => $request->state,
                    'postal_code' => $request->postal_code,
                    'country'     => $request->country,
                ]
            );

            // ✅ 2. Calculate total amount
            $totalAmount = collect($request->cart)->sum(fn($item) => $item['price'] * $item['quantity']);

            // ✅ 3. Create order
            $order = Order::create([
                'customer_id'   => $customer->id,
                'order_number'  => 'ORD-' . strtoupper(Str::random(8)),
                'total_amount'  => $totalAmount,
                'payment_method'=> $request->payment_method,
                'transaction_id'=> $request->transaction_id,
                'status'        => $request->payment_method === 'COD' ? 'pending' : 'paid',
            ]);

            // ✅ 4. Insert order items
            foreach ($request->cart as $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'total'      => $item['price'] * $item['quantity'],
                ]);
            }

            DB::commit();
           return response()->json([
    'message' => 'Order placed successfully!',
    'order' => [
        'order_number' => $order->order_number,
        'total_amount' => $order->total_amount,
        'payment_method' => $order->payment_method,
    ]
], 201);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
