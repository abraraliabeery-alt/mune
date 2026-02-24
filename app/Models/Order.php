<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = [
        'customer_name',
        'phone',
        'delivery_method',
        'location_url',
        'table_number',
        'public_code',
        'notes',
        'subtotal',
        'status',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
