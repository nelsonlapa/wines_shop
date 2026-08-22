<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Seat extends Model
{
    protected $fillable = [
        'event_id',
        'row',
        'number',
        'status',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELAÇÕES
    |--------------------------------------------------------------------------
    */

    // Lugar pertence a um evento
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    // Lugar pode ter uma inscrição (ou nenhuma)
    public function registration()
    {
        return $this->hasOne(Registration::class);
    }

    /*
    |--------------------------------------------------------------------------
    | HELPERS (opcional mas MUITO útil)
    |--------------------------------------------------------------------------
    */

    public function isAvailable()
    {
        return $this->status === 'available';
    }

    public function isSold()
    {
        return $this->status === 'sold';
    }

    public function isReserved()
    {
        return $this->status === 'reserved';
    }

    public function getLabelAttribute()
{
    return $this->row . $this->number;
}
}
