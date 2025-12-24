@extends('admin.layouts.app')


@section('content')
<div class="container">
<h2>Order Details</h2>


<p><strong>Order #:</strong> {{ $order->order_number }}</p>
<p><strong>Total:</strong> ₹{{ $order->total_amount }}</p>
<p><strong>Status:</strong> {{ ucfirst($order->status) }}</p>


<table class="table table-bordered mt-3">
<thead>
<tr>
<th>Product</th>
<th>Price</th>
<th>Qty</th>

</tr>
</thead>
<tbody>
@foreach($order->items as $item)
<tr>
<td>{{ $item->product->name ?? 'Deleted Product' }}</td>
<td>₹{{ $item->price }}</td>
<td>{{ $item->quantity }}</td>

</tr>
@endforeach
</tbody>
</table>
</div>
@endsection