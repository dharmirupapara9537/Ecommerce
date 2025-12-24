@extends('admin.layouts.app')


@section('content')
<div class="container">
<h2>All Orders</h2>


<table class="table table-bordered">
<thead>
<tr>
<th>Order</th>
<th>User</th>
<th>Total</th>
<th>Payment</th>
<th>Status</th>
<th>Date</th>
<th>Action</th>
</tr>
</thead>
<tbody>
@foreach($orders as $order)
<tr>
<td>{{ $order->order_number }}</td>
<td>{{ $order->user->name ?? 'Guest' }}</td>
<td>₹{{ $order->total_amount }}</td>
<td>{{ ucfirst($order->payment_method) }}</td>
<td>{{ ucfirst($order->status) }}</td>
<td>{{ $order->created_at->format('d M Y') }}</td>
<td>
<a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-primary">View</a>
</td>
</tr>
@endforeach
</tbody>
</table>
</div>
@endsection