<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;

class AdminOrderController extends Controller
{

  public function index(Request $request)
    {
        
      $query = Order::with('customer', 'items.product')->orderBy('created_at', 'desc');

        // Optional: search by order number or customer name
        if ($request->search) {
            $query->where('order_number', 'like', "%{$request->search}%")
                  ->orWhereHas('customer', function($q) use ($request) {
                      $q->where('name', 'like', "%{$request->search}%");
                  });
        }

        // Optional: filter by status
        if ($request->status) {
            $query->where('status', $request->status);
        }

        $orders = $query->paginate(10); // 10 per page

        return response()->json($orders);}
        

    

    
    public function updateStatus(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:pending,paid,completed,cancelled',
    ]);

    $order = Order::findOrFail($id);
    $order->status = $request->status;
    $order->save();

    return response()->json([
        'message' => 'Order status updated successfully',
        'order' => $order
    ]);
}

}
