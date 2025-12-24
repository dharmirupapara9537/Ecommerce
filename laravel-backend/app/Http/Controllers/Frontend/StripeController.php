<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use App\Models\Customer;
use App\Models\Order;
use App\Models\OrderItem;

class StripeController extends Controller
{
    public function process(Request $request)
    {
        // Validate customer & payment info
        $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'phone'=>'required',
            'address'=>'required',
            'city'=>'required',
            'state'=>'required',
            'postal_code'=>'required',
            'country'=>'required',
            'payment_method'=>'required|in:cod,card',
        ]);

        $cart = session('cart', []);
        if(empty($cart)) return back()->with('error','Cart is empty.');

        $total = collect($cart)->sum(fn($item)=>$item['price']*$item['quantity']);

        // Create or get customer
        $customer = Customer::firstOrCreate(
            ['email'=>$request->email],
            $request->only('name','phone','address','city','state','postal_code','country')
        );

        // Prepare order data
        $orderData = [
            'customer_id' => $customer->id,
            'order_number'=> 'ORD'.time(),
            'total_amount'=> $total,
            'payment_method'=> $request->payment_method,
            'status'=> $request->payment_method === 'cod' ? 'pending' : 'pending',
        ];

        // If payment method is card
        if($request->payment_method === 'card'){
            $request->validate(['payment_method_id'=>'required|string']);

            Stripe::setApiKey(config('services.stripe.secret'));

            try {
                $paymentIntent = PaymentIntent::create([
                    'amount' => $total * 100,
                    'currency' => 'inr',
                    'payment_method' => $request->payment_method_id,
                    'confirm' => true,
                    'automatic_payment_methods' => [
                        'enabled' => true,
                        'allow_redirects' => 'never',
                    ],
                ]);

                if($paymentIntent->status === 'succeeded'){
                    $orderData['status'] = 'paid';
                    $orderData['transaction_id'] = $paymentIntent->id;
                } else {
                    return back()->with('error','Payment failed: '.$paymentIntent->status);
                }
            } catch(\Exception $e){
                return back()->with('error',$e->getMessage());
            }
        }

        // Save order
        $order = Order::create($orderData);

        // Save order items
        foreach($cart as $productId => $item){
            OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $productId,
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity'],
            ]);
        }

        session()->forget('cart');
        return redirect()->route('thank.you');
    }

    public function thankYou()
    {
        return view('frontend.thankyou');
    }
}
