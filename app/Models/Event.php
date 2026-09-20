<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
class Event extends Model
{
    protected $fillable = [
        'title',
        'producer',
        'country',
        'wine_region',
        'winemaker',
        'alcohol_percentage',
        'bottle_capacity',
        'grapes',
        'description',
        'date',
        'city',
        'price',
        'discount_percentage',
        'capacity',
        'status',
        'organizer_id',
        'category_id',
        'image',
        'visibility',
        'private_token',
        'latitude',
        'longitude',
        'address',
        'rows',
    'seats_per_row',
    'has_seats'
    ];
protected $casts = [
    'date' => 'datetime',
    'alcohol_percentage' => 'decimal:1',
    'discount_percentage' => 'decimal:2',
     'latitude' => 'float',
    'longitude' => 'float',
];
    public function organizer()
    {
        return $this->belongsTo(User::class, 'organizer_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function getSalePriceAttribute(): float
    {
        $basePrice = $this->price ?: 12.50;

        return round($basePrice * (1 - (($this->discount_percentage ?? 0) / 100)), 2);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    protected static function booted()
{
    static::creating(function ($event) {
        if (empty($event->date)) {
            $event->date = now();
        }

        if ($event->visibility === 'private' && empty($event->private_token)) {
            $event->private_token = Str::uuid()->toString();
        }
    });

    static::created(function ($event) {

        // 🔥 CRIAR LUGARES AUTOMATICAMENTE
        if ($event->has_seats && $event->rows && $event->seats_per_row) {

            for ($row = 1; $row <= $event->rows; $row++) {
                for ($seat = 1; $seat <= $event->seats_per_row; $seat++) {

                    \App\Models\Seat::create([
                        'event_id' => $event->id,
                        'row' => chr(64 + $row), // A, B, C...
                        'number' => $seat,
                        'status' => 'available',
                    ]);

                }
            }
        }
    });

    static::updating(function ($event) {
        if ($event->visibility === 'private' && empty($event->private_token)) {
            $event->private_token = Str::uuid()->toString();
        }

        if ($event->visibility === 'public') {
            $event->private_token = null;
        }
    });
}
public function images()
{
    return $this->hasMany(EventImage::class);
}
public function seats()
{
    return $this->hasMany(Seat::class);
}

public function artists()
{
    return $this->belongsToMany(Artist::class, 'event_artist')
        ->withPivot('is_headliner')
        ->withTimestamps();
}

public function eventArtists()
{
    return $this->hasMany(EventArtist::class);
}
public function ticketTypes()
{
    return $this->hasMany(TicketType::class);
}
public function staff()
{
    return $this->belongsToMany(
        User::class,
        'event_staff'
    );
}
}
