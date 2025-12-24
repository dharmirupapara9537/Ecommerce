<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{
    


public function index()
{
$orders = Order::orderBy('created_at', 'desc')->get();
return view('admin.orders.index', compact('orders'));
}
public function show($id)
{
$order = Order::with('items.product')->findOrFail($id);
return view('admin.orders.show', compact('order'));
}
}
