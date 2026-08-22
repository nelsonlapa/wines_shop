<?php

namespace App\Filament\Resources\Events\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Columns\ImageColumn;
class EventsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('title')
                    ->searchable(),
                    ImageColumn::make('image')
    ->label('Poster')
    ->getStateUsing(fn ($record) => asset('storage/' . $record->image))
    ->square(),
                TextColumn::make('date')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('location')
                    ->searchable(),
                TextColumn::make('price')
                    ->money()
                    ->sortable(),
                TextColumn::make('capacity')
    ->label('Inscrições')
    ->formatStateUsing(function ($record) {
        $count = $record->registrations()->count();
        return $count . ' / ' . $record->capacity;
    })
    ->badge()
    ->color(function ($record) {
        return $record->registrations()->count() >= $record->capacity
            ? 'danger'
            : 'success';
    }),
                TextColumn::make('status')
                    ->badge(),
                TextColumn::make('organizer.name')
    ->label('Organizador')
    ->searchable()
    ->sortable(),

TextColumn::make('category.name')
    ->label('Categoria')
    ->searchable()
    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('visibility')
    ->label('Link')
    ->badge()
    ->formatStateUsing(function ($record) {
        return $record->visibility === 'private'
            ? '🔒 Privado'
            : '🌍 Público';
    })
    ->color(function ($record) {
        return $record->visibility === 'private'
            ? 'warning'   // Laranja
            : 'primary';  // Azul
    })
    ->copyable()
    ->copyableState(function ($record) {
        if ($record->visibility === 'private') {
            return url('/convite/' . $record->private_token);
        }

        return route('events.show', $record);
    })
    ->copyMessage('Link copiado!')
    ->copyMessageDuration(1500),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
