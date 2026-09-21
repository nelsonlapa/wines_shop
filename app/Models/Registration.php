<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'order_reference',
        'order_id',
        'user_id',
        'customer_name',
        'address',
        'postal_code',
        'city',
        'phone',
        'delivery_method',
        'shipping_cost',
        'stripe_session_id',
        'event_id',
        'status',
        'ticket_token',
        'seat_id',
        'checked_in',
        'checked_in_at',
    ];
    protected $casts = [
        'checked_in' => 'boolean',
        'checked_in_at' => 'datetime',
        'shipping_cost' => 'decimal:2',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
    public function seat()
{
    return $this->belongsTo(Seat::class);
}
}
