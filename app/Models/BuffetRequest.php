<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BuffetRequest extends Model
{
    protected $fillable = [
        'public_code',
        'customer_name',
        'phone',
        'company_name',
        'people_count',
        'event_at',
        'details',
        'status',
        'quote_amount',
        'quote_message',
        'quoted_at',
        'created_by_user_id',
    ];

    protected $casts = [
        'event_at' => 'datetime',
        'quoted_at' => 'datetime',
    ];

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by_user_id');
    }
}
