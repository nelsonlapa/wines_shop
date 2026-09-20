<?php

namespace App\Filament\Resources\Events\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
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

                    Section::make('Informações do produto')
                        ->schema([

                            TextInput::make('title')
                                ->label('Nome do vinho')
                                ->required(),

                            TextInput::make('producer')
                                ->label('Produtor')
                                ->placeholder('Ex.: Symington Family Estates')
                                ->required(),

                            TextInput::make('country')
                                ->label('País')
                                ->default('Portugal')
                                ->required(),

                            TextInput::make('wine_region')
                                ->label('Região')
                                ->placeholder('Ex.: Douro'),

                            TextInput::make('winemaker')
                                ->label('Enólogo')
                                ->placeholder('Ex.: Charles Symington'),

                            TextInput::make('alcohol_percentage')
                                ->label('Teor de álcool (%)')
                                ->numeric()
                                ->step(0.1)
                                ->suffix('%'),

                            TextInput::make('bottle_capacity')
                                ->label('Capacidade')
                                ->placeholder('Ex.: 0.75 L'),

                            Textarea::make('grapes')
                                ->label('Castas')
                                ->placeholder('Ex.: Touriga Nacional, Touriga Franca, Sousão')
                                ->columnSpanFull(),

                            FileUpload::make('image')
                                ->label('Imagem do produto')
                                ->image()
                                ->disk('public')
                                ->directory('events')
                                ->imagePreviewHeight('200'),

                            Textarea::make('description')
                                ->label('Descrição')
                                ->required()
                                ->columnSpanFull(),

                            Hidden::make('date'),

                            Select::make('category_id')
                                ->label('Categoria')
                                ->relationship('category', 'name')
                                ->required(),

                            Hidden::make('organizer_id')
                                ->default(fn () => auth()->id()),

                        ])
                        ->columns(2),

                ]),

            // TAB 2
            Tab::make('Preço e stock')
                ->icon('heroicon-o-shopping-bag')
                ->schema([

                    Section::make('Configuração')
                        ->schema([

                            TextInput::make('capacity')
                                ->label('Stock total')
                                ->numeric()
                                ->required(),

                            TextInput::make('price')
                                ->label('Preço por garrafa')
                                ->numeric()
                                ->default(0)
                                ->prefix('€'),

                            TextInput::make('discount_percentage')
                                ->label('Desconto')
                                ->numeric()
                                ->default(0)
                                ->minValue(0)
                                ->maxValue(100)
                                ->suffix('%'),

                        ])
                        ->columns(2),

                ]),

            // TAB 3
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

            // TAB 4
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
