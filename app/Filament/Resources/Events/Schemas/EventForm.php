<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Fahiem\FilamentPinpoint\Pinpoint;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\Repeater;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;

class EventForm
{
    public static function configure(Schema $schema): Schema
    {
       return $schema->components([

    Tabs::make('Gerir Produto')
        ->tabs([

            // TAB 1
            Tab::make('Informações')
                ->icon('heroicon-o-document-text')
                ->schema([

                    Section::make('Informações do Evento')
                        ->schema([

                            TextInput::make('title')
                                ->label('Nome do vinho')
                                ->required(),

                            FileUpload::make('image')
                                ->label('Imagem do produto')
                                ->image()
                                ->disk('public')
                                ->directory('events')
                                ->imagePreviewHeight('200'),

                            Textarea::make('description')
                                ->label('Descrição e notas de prova')
                                ->required()
                                ->columnSpanFull(),

                            DateTimePicker::make('date')
                                ->label('Data de entrada no catálogo')
                                ->required(),

                            Select::make('category_id')
                                ->label('Categoria')
                                ->relationship('category', 'name')
                                ->required(),

                            auth()->user()?->isAdmin()

                                ? Select::make('organizer_id')
                                    ->label('Organizador')
                                    ->relationship(
                                        'organizer',
                                        'name',
                                        fn ($query) => $query->whereHas('role', function ($q) {
                                            $q->where('name', 'Organizador');
                                        })
                                    )
                                    ->searchable()
                                    ->preload()
                                    ->required()

                                : Hidden::make('organizer_id')
                                    ->default(auth()->id()),

                        ])
                        ->columns(2),

                ]),

            // TAB 2
            Tab::make('Origem e localização')
                ->icon('heroicon-o-map-pin')
                ->schema([

                    Section::make('Origem, produtor e entrega')
                        ->schema([

                            Pinpoint::make('map')
                                ->label('Região de origem')
                                ->defaultLocation(41.1579, -8.6291)
                                ->defaultZoom(15)
                                ->height(500)
                                ->draggable()
                                ->searchable()
                                ->latField('latitude')
                                ->lngField('longitude')
                                ->addressField('address')
                                ->cityField('city'),

                            Hidden::make('address'),
                            Hidden::make('city'),
                            Hidden::make('latitude'),
                            Hidden::make('longitude'),

                        ]),

                ]),

            // TAB 3
            Tab::make('Preço e stock')
                ->icon('heroicon-o-shopping-bag')
                ->schema([

                    Section::make('Configuração')
                        ->schema([

                            Toggle::make('has_seats')
                                ->label('Produto com variantes/lotes?')
                                ->live(),

                            TextInput::make('capacity')
                                ->label('Stock total')
                                ->numeric()
                                ->required(),

                            TextInput::make('price')
                                ->label('Preço por garrafa')
                                ->numeric()
                                ->default(0)
                                ->prefix('€'),

                            TextInput::make('rows')
                                ->label('Número de lotes')
                                ->numeric()
                                ->visible(fn ($get) => $get('has_seats')),

                            TextInput::make('seats_per_row')
                                ->label('Unidades por lote')
                                ->numeric()
                                ->visible(fn ($get) => $get('has_seats')),

                        ])
                        ->columns(2),

                    Section::make('Formatos e preços')
                        ->schema([

                            Repeater::make('ticketTypes')
                                ->relationship()
                                ->schema([

                                    TextInput::make('name')
                                        ->label('Formato / lote')
                                        ->required(),

                                    TextInput::make('price')
                                        ->numeric()
                                        ->required()
                                        ->prefix('€'),

                                    TextInput::make('quantity')
                                        ->label('Stock disponível')
                                        ->numeric()
                                        ->required(),

                                ])
                                ->columns(3)
                                ->visible(fn ($get) => !$get('has_seats')),

                        ]),

                ]),

            // TAB 4
            Tab::make('Produtor')
                ->icon('heroicon-o-building-storefront')
                ->schema([

                    Section::make('Produtor / quinta')
                        ->schema([

                            Repeater::make('eventArtists')
                                ->relationship()
                                ->schema([

                                    Select::make('artist_id')
                                        ->relationship('artist', 'name')
                                        ->searchable()
                                        ->required(),

                                    Toggle::make('is_headliner')
                                        ->label('Produtor principal'),

                                ])
                                ->columns(2),

                        ]),

                ]),

            // TAB 5
            Tab::make('Galeria do produto')
                ->icon('heroicon-o-photo')
                ->schema([

                    Section::make('Fotografias da garrafa e quinta')
                        ->schema([

                            Repeater::make('images')
                                ->relationship()
                                ->schema([

                                    FileUpload::make('image')
                                        ->image()
                                        ->disk('public')
                                        ->directory('events/gallery')
                                        ->required(),

                                ]),

                        ]),

                ]),

            // TAB 6
            Tab::make('Equipa da loja')
                ->icon('heroicon-o-users')
                ->schema([

                    Section::make('Equipa responsável')
                        ->schema([

                            Select::make('staff')
                                ->label('Membros da equipa')
                                ->multiple()
                                ->relationship(
                                    'staff',
                                    'name',
                                    fn ($query) => $query->whereHas('role', function ($q) {
                                        $q->where('name', 'Staff');
                                    })
                                )
                                ->searchable()
                                ->preload(),

                        ]),

                ]),

            // TAB 7
            Tab::make('Estado')
                ->icon('heroicon-o-cog-6-tooth')
                ->schema([

                    Section::make('Publicação do produto')
                        ->schema([

                            Select::make('visibility')
                                ->options([
                                    'public' => 'Público',
                                    'private' => 'Acesso reservado',
                                ])
                                ->default('public'),

                            Select::make('status')
                                ->options([
                                    'draft' => 'Rascunho',
                                    'active' => 'Disponível',
                                    'cancelled' => 'Indisponível',
                                ])
                                ->default('draft'),

                        ])
                        ->columns(2),

                ]),

        ])
        ->persistTabInQueryString()
        ->columnSpanFull(),

]);
    }
}
