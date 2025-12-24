<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'order_number',
        'total_amount',
        'payment_method',
        'transaction_id',
        'status',
    ];

    // Belongs to one customer
    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    // One order has many items
    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
