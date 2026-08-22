<?php

namespace App\Filament\Resources\Roles;

use App\Filament\Resources\Roles\Pages\CreateRole;
use App\Filament\Resources\Roles\Pages\EditRole;
use App\Filament\Resources\Roles\Pages\ListRoles;
use App\Filament\Resources\Roles\Schemas\RoleForm;
use App\Filament\Resources\Roles\Tables\RolesTable;
use App\Models\Role;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class RoleResource extends Resource
{
    protected static ?string $model = Role::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Utilizadores';
    protected static ?string $recordTitleAttribute = 'name';
protected static ?string $navigationLabel = 'Perfis';
protected static ?string $modelLabel = 'Perfil';
protected static ?string $pluralModelLabel = 'Perfis';
    public static function form(Schema $schema): Schema
    {
        return RoleForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RolesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoles::route('/'),
            'create' => CreateRole::route('/create'),
            'edit' => EditRole::route('/{record}/edit'),
        ];
    }
    public static function canViewAny(): bool
    {
        return auth()->user()?->isAdmin() ?? false;
    }

public static function canCreate(): bool
{
        return auth()->user()?->isAdmin() ?? false;
    }
  public static function canEdit(Model $record): bool
{
    return auth()->user()?->isAdmin() ?? false;
}

public static function canDelete(Model $record): bool
{
    if ($record->name === 'Admin') {
        return false;
    }

    return auth()->user()?->isAdmin() ?? false;
}
}
