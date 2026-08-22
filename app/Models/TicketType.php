<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TicketType extends Model
{
    protected $fillable = [
        'event_id',
        'name',
        'price',
        'quantity',
        'sold',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // lugares disponíveis
    public function getAvailableAttribute()
    {
        return $this->quantity - $this->sold;
    }
}
