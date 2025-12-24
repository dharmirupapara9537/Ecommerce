<?php
namespace App\Http\Controllers\Frontend;
use App\Http\Controllers\Controller; 


use App\Models\Order;
use App\Models\OrderItem;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Stripe\Stripe;
use Stripe\PaymentIntent;
use Illuminate\Support\Facades\DB; 

class CheckoutController extends Controller
{
    // Show checkout page
    public function index()
    {
        $cart = session()->get('cart', []);
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }
        return view('frontend.checkout', compact('cart', 'total'));
    }

    // Handle checkout
    public function store(Request $request)
    {
        $request->validate([
            'name'   => 'required|string|max:150',
            'email'  => 'required|email',
            'phone'  => 'required|string|max:20',
        ]);

        // Fake logged-in customer (if no auth)
        $customer = \App\Models\Customer::firstOrCreate(
            ['email' => $request->email],
            [
                'name'  => $request->name,
                'phone' => $request->phone,
                'address'    => $request->address,
                'city'       => $request->city,
                'state'      => $request->state,
                'postal_code'=> $request->postal_code,
                'country'    => $request->country,
            ]
        );

        // Get cart
        $cart = session()->get('cart', []);
        if (empty($cart)) {
            return redirect()->back()->with('error', 'Your cart is empty.');
        }

        // Calculate total
        $totalAmount = 0;
        foreach ($cart as $item) {
            $totalAmount += $item['price'] * $item['quantity'];
        }

        // Create order
        $order = Order::create([
            'customer_id'   => $customer->id,
            'order_number'  => 'ORD' . strtoupper(Str::random(8)),
            'total_amount'  => $totalAmount,
            'payment_method'=> 'cod',
            'status'        => 'pending',
        ]);

        // Add order items
        foreach ($cart as $productId => $item) {
            OrderItem::create([
                'order_id'   => $order->id,
                'product_id' => $productId,
                'quantity'   => $item['quantity'],
                'price'      => $item['price'],
                'total'      => $item['price'] * $item['quantity'],
            ]);
        }

        // Clear cart
        session()->forget('cart');

        return redirect()->route('order.thankyou', $order->id);
    }

    // Thank you page
    public function thankyou($id)
    {
        $order = Order::with('items.product', 'customer')->findOrFail($id);
        return view('frontend.thankyou', compact('order'));
    }

    

public function showStripeForm()
    {
        $cart = session()->get('cart', []);
        return view('frontend.payment', compact('cart'));
    }

    public function processStripePayment(Request $request)
    {
        try {
            // ✅ Stripe secret key
            Stripe::setApiKey(env('STRIPE_SECRET'));

            // total amount from cart
            $cart = $request->cart ?? [];
            $total = collect($cart)->sum(function ($item) {
                return $item['price'] * $item['quantity'];
            });

            // ✅ Create PaymentIntent
$paymentIntent = PaymentIntent::create([
            'amount' => $total * 100,
            'currency' => 'inr',
            'payment_method' => $request->payment_method,
            'confirm' => true,
            // choose one: don't include confirmation_method if using automatic_payment_methods
            'automatic_payment_methods' => [
                'enabled' => true,
                'allow_redirects' => 'never' // optional
            ],
        ]);
            // ✅ Save order in DB
            DB::beginTransaction();

             $customer = \App\Models\Customer::firstOrCreate(
            ['email' => $request->email],
            [
                'name'  => $request->name,
                'phone' => $request->phone,
                'address'    => $request->address,
                'city'       => $request->city,
                'state'      => $request->state,
                'postal_code'=> $request->postal_code,
                'country'    => $request->country,
            ]
        );


            $order = Order::create([
            'customer_id'   => $customer->id,
            'order_number'  => 'ORD' . strtoupper(Str::random(8)),
            'total_amount'  => $totalAmount,
            'payment_method'=> 'stripe',
            'transaction_id'=> $paymentIntent->id,
            'status'        => 'paid',
        ]);
          
            foreach ($cart as $productId => $item) {
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $productId,
                    'quantity'   => $item['quantity'],
                    'price'      => $item['price'],
                    'total'      => $item['price'] * $item['quantity'],
                ]);
            }



            DB::commit();

            session()->forget('cart');

            return response()->json([
                'success' => true,
                'order_id' => $order->id,
                'transaction_id' => $paymentIntent->id,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
