<?php

namespace App\Filament\Resources\Artists\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\FileUpload;
use Filament\Schemas\Schema;

class ArtistForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([

                TextInput::make('name')
                    ->label('Nome do produtor / quinta')
                    ->required()
                    ->maxLength(255),

                FileUpload::make('image')
                    ->label('Imagem do produtor / quinta')
                    ->image()
                    ->disk('public')
                    ->directory('artists')
                    ->imagePreviewHeight('150')
                    ->nullable(),

            ]);
    }
}
