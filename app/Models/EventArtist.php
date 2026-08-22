<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EventArtist extends Model
{
    protected $table = 'event_artist';

    protected $fillable = [
        'event_id',
        'artist_id',
        'is_headliner'
    ];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
