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
        'loyalty_phone',
        'loyalty_points',
        'notes',
        'subtotal',
        'status',
        'created_by_user_id',
        'updated_by_user_id',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }

    public function updatedBy()
    {
        return $this->belongsTo(User::class, 'updated_by_user_id');
    }

    public function items()
    {
        return $this->hasMany(OrderItem::class);
    }
}
