<?php

namespace App\Filament\Resources\Categories\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class CategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->label('Nome da categoria')
                    ->required(),

                Textarea::make('description')
                    ->label('Descrição da categoria')
                    ->default(null)
                    ->columnSpanFull(),

                FileUpload::make('image')
                    ->label('Imagem da categoria')
                    ->image()
                    ->directory('images/categories') // vai para storage/app/public/images/categories
                    ->disk('public')
                    ->required(),
            ]);
    }
}
