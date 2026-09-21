<?php

namespace App\Models;

use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable implements FilamentUser
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /*
    |--------------------------------------------------------------------------
    | RELAÇÕES
    |--------------------------------------------------------------------------
    */

    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function registrations()
    {
        return $this->hasMany(Registration::class);
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    /*
    |--------------------------------------------------------------------------
    | ROLES
    |--------------------------------------------------------------------------
    */

    public function hasRole(string $role): bool
    {
        return $this->role?->name === $role;
    }

    public function isAdmin(): bool
    {
        return $this->hasRole('Admin');
    }

    public function isOrganizador(): bool
    {
        return $this->hasRole('Organizador');
    }

    public function isStaff(): bool
    {
        return $this->hasRole('Staff');
    }

    public function isParticipante(): bool
    {
        return $this->hasRole('Participante');
    }
        public function staffEvents()
    {
        return $this->belongsToMany(
            Event::class,
            'event_staff'
        );
    }
    public function can($ability, $arguments = [])
{
    if ($ability === 'access_filemanager') {
        return $this->isAdmin();
    }

    return parent::can($ability, $arguments);
}
    /*
    |--------------------------------------------------------------------------
    | FILAMENT
    |--------------------------------------------------------------------------
    */

    public function canAccessPanel(Panel $panel): bool
    {
        return (
            $this->isAdmin()
            || $this->isOrganizador()
            || $this->isStaff()
        );
    }

    /*
    |--------------------------------------------------------------------------
    | PERMISSÕES
    |--------------------------------------------------------------------------
    */

    public function canValidateCheckin(): bool
    {
        return (
            $this->isAdmin()
            || $this->isOrganizador()
            || $this->isStaff()
        );
    }
}
