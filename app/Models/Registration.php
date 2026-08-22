<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Registration extends Model
{
    protected $fillable = [
        'user_id',
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
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
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
