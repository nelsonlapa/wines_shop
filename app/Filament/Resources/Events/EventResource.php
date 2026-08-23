<?php

namespace App\Filament\Resources\Events;

use App\Filament\Resources\Events\Pages\CreateEvent;
use App\Filament\Resources\Events\Pages\EditEvent;
use App\Filament\Resources\Events\Pages\ListEvents;
use App\Filament\Resources\Events\Schemas\EventForm;
use App\Filament\Resources\Events\Tables\EventsTable;
use App\Models\Event;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use App\Models\Seat;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;


class EventResource extends Resource
{
    protected static ?string $model = Event::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;
    protected static string|UnitEnum|null $navigationGroup = 'Gestão';
    protected static ?string $recordTitleAttribute = 'title';
    protected static ?string $navigationLabel = 'Produtos';
    protected static ?string $modelLabel = 'Produto';
    protected static ?string $pluralModelLabel = 'Produtos';
    public static function form(Schema $schema): Schema
    {
        return EventForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return EventsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }
    protected static function afterCreate($record): void
    {
        foreach (range(1, $record->rows) as $rowIndex) {

            $rowLetter = chr(64 + $rowIndex); // A, B, C...

            for ($i = 1; $i <= $record->seats_per_row; $i++) {

                Seat::create([
                    'event_id' => $record->id,
                    'row' => $rowLetter,
                    'number' => $i,
                ]);
            }
        }
    }
    public static function getPages(): array
    {
        return [
            'index' => ListEvents::route('/'),
            'create' => CreateEvent::route('/create'),
            'edit' => EditEvent::route('/{record}/edit'),
        ];
    }

    public static function getEloquentQuery(): Builder
{
    $query = parent::getEloquentQuery();

    $user = auth()->user();

    if ($user?->isAdmin()) {
        return $query;
    }

    if ($user?->isOrganizador()) {
        return $query->where('organizer_id', $user->id);
    }

    return $query->whereRaw('1 = 0');
}

   public static function canViewAny(): bool
{
    $user = auth()->user();

    return (
        $user?->isAdmin()
        || $user?->isOrganizador()
    );
}

    public static function canCreate(): bool
{
    $user = auth()->user();

    return (
        $user?->isAdmin()
        || $user?->isOrganizador()
    );
}

    public static function canEdit(Model $record): bool
    {
        $user = auth()->user();

        return $user?->isAdmin()
            || ($user?->isOrganizador() && $record->organizer_id === $user->id);
    }

    public static function canDelete(Model $record): bool
    {
        $user = auth()->user();

        return $user?->isAdmin()
            || ($user?->isOrganizador() && $record->organizer_id === $user->id);
    }
}
