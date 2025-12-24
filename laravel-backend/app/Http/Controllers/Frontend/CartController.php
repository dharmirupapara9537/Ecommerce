<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

class CartController extends Controller
{
    // Show cart page
    public function index()
    {
        $cart = session()->get('cart', []);

        // Calculate total
        $total = 0;
        foreach($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return view('frontend.cart.index', compact('cart', 'total'));
    }

    // Add product to cart
    public function add(Request $request, $id)
    {
        $product = Product::with(['primaryImage', 'images'])->findOrFail($id);

        $image = $product->primaryImage?->image_path 
                 ?? $product->images->first()?->image_path 
                 ?? 'default.png';

        $cart = session()->get('cart', []);

        if(isset($cart[$id])) {
            $cart[$id]['quantity'] += 1;
        } else {
            $cart[$id] = [
                'name' => $product->name,
                'quantity' => 1,
                'price' => $product->price,
                'image' => $image,
            ];
        }

        session()->put('cart', $cart);

        return redirect()->route('cart.index')->with('success', 'Product added to cart!');
    }

    // Update quantity
    public function update(Request $request, $id)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$id])) {
        $cart[$id]['quantity'] = max(1, (int)$request->quantity);
        session()->put('cart', $cart);
    }

     return redirect()->route('cart.index')->with('success', 'Cart updated successfully!');
}

    // Remove from cart
  public function remove(Request $request, $id)
{
    $cart = session()->get('cart', []);

    if(isset($cart[$id])) {
        unset($cart[$id]);
        session()->put('cart', $cart);
    }

    return redirect()->route('cart.index')->with('success', 'Product removed successfully!');
}
public function clear()
{
    session()->forget('cart'); // removes the 'cart' session entirely
    return redirect()->route('cart.index')->with('success', 'Cart cleared successfully!');
}

}
