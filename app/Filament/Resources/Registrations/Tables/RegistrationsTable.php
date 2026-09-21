<?php

namespace App\Filament\Resources\Registrations\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Tables\Grouping\Group;

class RegistrationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order.order_reference')
                    ->label('Encomenda')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('user.name')
                    ->label('Utilizador')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('event.title')
                    ->label('Produto')
                    ->searchable()
                    ->sortable(),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                    
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->groups([
                Group::make('event_id')
                    ->label('Produto')
                    ->getTitleFromRecordUsing(fn ($record) => $record->event?->title ?? 'Evento removido')
                    ->collapsible(),
            ])
            ->defaultGroup('event_id')
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
