<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name',
        'image',
    ];

    /**
     * Relação com eventos (many-to-many)
     */
public function events()
{
    return $this->belongsToMany(Event::class, 'event_artist')
        ->withPivot('is_headliner')
        ->withTimestamps();
}
}
