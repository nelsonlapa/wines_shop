<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    protected $fillable = [
        'order_reference',
        'number',
        'user_id',
        'customer_name',
        'address',
        'postal_code',
        'city',
        'phone',
        'delivery_method',
        'shipping_cost',
        'stripe_session_id',
        'subtotal',
        'total',
        'shipping_name',
        'shipping_address',
        'shipping_city',
        'shipping_postcode',
        'shipping_country',
        'status',
    ];

    protected $casts = [
        'shipping_cost' => 'decimal:2',
        'subtotal' => 'decimal:2',
        'total' => 'decimal:2',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function registrations(): HasMany
    {
        return $this->hasMany(Registration::class);
    }
}
