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

    Tabs::make('Criar Evento')
        ->tabs([

            // TAB 1
            Tab::make('Informações')
                ->icon('heroicon-o-document-text')
                ->schema([

                    Section::make('Informações do Evento')
                        ->schema([

                            TextInput::make('title')
                                ->required(),

                            FileUpload::make('image')
                                ->label('Cartaz do Evento')
                                ->image()
                                ->disk('public')
                                ->directory('events')
                                ->imagePreviewHeight('200'),

                            Textarea::make('description')
                                ->required()
                                ->columnSpanFull(),

                            DateTimePicker::make('date')
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
            Tab::make('Localização')
                ->icon('heroicon-o-map-pin')
                ->schema([

                    Section::make('Localização')
                        ->schema([

                            Pinpoint::make('map')
                                ->label('Localização')
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
            Tab::make('Bilhetes')
                ->icon('heroicon-o-ticket')
                ->schema([

                    Section::make('Configuração')
                        ->schema([

                            Toggle::make('has_seats')
                                ->label('Tem lugares marcados?')
                                ->live(),

                            TextInput::make('capacity')
                                ->numeric()
                                ->required(),

                            TextInput::make('price')
                                ->numeric()
                                ->default(0)
                                ->prefix('€'),

                            TextInput::make('rows')
                                ->label('Número de filas')
                                ->numeric()
                                ->visible(fn ($get) => $get('has_seats')),

                            TextInput::make('seats_per_row')
                                ->label('Lugares por fila')
                                ->numeric()
                                ->visible(fn ($get) => $get('has_seats')),

                        ])
                        ->columns(2),

                    Section::make('Tipos de Bilhete')
                        ->schema([

                            Repeater::make('ticketTypes')
                                ->relationship()
                                ->schema([

                                    TextInput::make('name')
                                        ->required(),

                                    TextInput::make('price')
                                        ->numeric()
                                        ->required()
                                        ->prefix('€'),

                                    TextInput::make('quantity')
                                        ->numeric()
                                        ->required(),

                                ])
                                ->columns(3)
                                ->visible(fn ($get) => !$get('has_seats')),

                        ]),

                ]),

            // TAB 4
            Tab::make('Artistas')
                ->icon('heroicon-o-musical-note')
                ->schema([

                    Section::make('Artistas')
                        ->schema([

                            Repeater::make('eventArtists')
                                ->relationship()
                                ->schema([

                                    Select::make('artist_id')
                                        ->relationship('artist', 'name')
                                        ->searchable()
                                        ->required(),

                                    Toggle::make('is_headliner')
                                        ->label('Cabeça de cartaz'),

                                ])
                                ->columns(2),

                        ]),

                ]),

            // TAB 5
            Tab::make('Galeria')
                ->icon('heroicon-o-photo')
                ->schema([

                    Section::make('Galeria do Evento')
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
            Tab::make('Staff')
                ->icon('heroicon-o-users')
                ->schema([

                    Section::make('Staff do Evento')
                        ->schema([

                            Select::make('staff')
                                ->label('Membros Staff')
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

                    Section::make('Estado')
                        ->schema([

                            Select::make('visibility')
                                ->options([
                                    'public' => 'Público',
                                    'private' => 'Privado',
                                ])
                                ->default('public'),

                            Select::make('status')
                                ->options([
                                    'draft' => 'Draft',
                                    'active' => 'Active',
                                    'cancelled' => 'Cancelled',
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
