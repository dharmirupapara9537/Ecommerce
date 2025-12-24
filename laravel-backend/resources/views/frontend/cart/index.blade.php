@extends('frontend.layouts.app')

@section('content')
<div class="container"><br>
    <div class="mb-4">
        <div class="d-flex justify-content-between mb-3">
    <!-- Continue Shopping Button (Left) -->
    <a href="{{ url('/') }}" class="btn btn-primary">Continue Shopping</a>

    <!-- Clear Cart Button (Right) -->
    @if(count($cart) > 0)
        <form action="{{ route('cart.clear') }}" method="POST">
            @csrf
            <button type="submit" class="btn btn-danger">Clear Cart</button>
        </form>
    @endif
</div>

   
    </div>
    <h2>Your Shopping Cart</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(count($cart) > 0)
    <table class="table table-bordered">
        <tr>
            <th>Image</th><th>Name</th><th>Quantity</th><th>Price</th><th>Subtotal</th><th>Action</th>
        </tr>
        @foreach($cart as $id => $item)
        <tr>
            <td><img src="{{ asset('storage/products/' . $item['image']) }}" width="70"></td>
            <td>{{ $item['name'] }}</td>
            <td>
                <form action="{{ route('cart.update', $id) }}" method="POST" style="display:inline;">
    @csrf
    <input type="number" name="quantity" value="{{ $item['quantity'] }}" min="1" style="width:60px;">
    <button type="submit" class="btn btn-sm btn-primary">Update</button>
</form>

            </td>
            <td>₹{{ $item['price'] }}</td>
            <td>₹{{ $item['price'] * $item['quantity'] }}</td>
            <td>
                <form action="{{ route('cart.remove', $id) }}" method="POST">
                    @csrf
                    
                    <button class="btn btn-sm btn-danger">Remove</button>
                </form>
            </td>
        </tr>
        @endforeach
        <tr>
            <td colspan="4" class="text-end"><strong>Total:</strong></td>
            <td colspan="2"><strong>₹{{ number_format($total, 2) }}</strong></td>
        </tr>
    </table>
      <a href="{{ route('checkout.index')}}" class="primary-btn">Proceed to Checkout</a>
    @else
    <p>Your cart is empty.</p>
    @endif
</div>
@endsection
