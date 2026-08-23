<?php

namespace App\Filament\Resources\Registrations\Schemas;

use Filament\Forms\Components\Select;
use Filament\Schemas\Schema;

class RegistrationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('Cliente')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->required(),

                Select::make('event_id')
                    ->label('Produto')
                    ->relationship('event', 'title')
                    ->searchable()
                    ->required(),

                Select::make('status')
                    ->options([
                        'pending' => 'Pendente',
                        'confirmed' => 'Confirmada',
                        'cancelled' => 'Cancelada',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }
}
