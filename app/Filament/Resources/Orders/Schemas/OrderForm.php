<?php

namespace App\Filament\Resources\Orders\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class OrderForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('order_reference')
                ->label('Referência')
                ->disabled(),
            Select::make('status')
                ->label('Estado')
                ->options([
                    'paid' => 'Confirmada',
                    'cancelled' => 'Cancelada',
                ])
                ->required(),
            TextInput::make('customer_name')->label('Cliente')->disabled(),
            TextInput::make('phone')->label('Telemóvel')->disabled(),
            Select::make('delivery_method')
                ->label('Método de entrega')
                ->options([
                    'delivery' => 'Entrega ao domicílio',
                    'pickup' => 'Levantamento na loja',
                ])
                ->disabled(),
            TextInput::make('shipping_cost')->label('Custo de envio')->prefix('€')->disabled(),
            Textarea::make('address')->label('Morada')->disabled()->columnSpanFull(),
            TextInput::make('postal_code')->label('Código postal')->disabled(),
            TextInput::make('city')->label('Cidade')->disabled(),
        ]);
    }
}
