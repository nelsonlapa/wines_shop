<?php

namespace App\Filament\Resources\Orders\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('order_reference')->label('Referência')->searchable()->sortable(),
                TextColumn::make('customer_name')->label('Cliente')->searchable()->sortable(),
                TextColumn::make('registrations_count')->counts('registrations')->label('Produtos')->sortable(),
                TextColumn::make('delivery_method')
                    ->label('Entrega')
                    ->formatStateUsing(fn (?string $state): string => $state === 'pickup' ? 'Levantamento' : 'Entrega ao domicílio'),
                TextColumn::make('status')
                    ->label('Estado')
                    ->badge()
                    ->formatStateUsing(fn (string $state): string => $state === 'paid' ? 'Confirmada' : 'Cancelada')
                    ->color(fn (string $state): string => $state === 'paid' ? 'success' : 'danger'),
                TextColumn::make('created_at')->label('Data')->dateTime('d/m/Y H:i')->sortable(),
            ])
            ->filters([])
            ->recordActions([EditAction::make()])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
