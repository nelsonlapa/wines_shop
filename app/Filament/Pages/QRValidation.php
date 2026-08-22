<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Pages\Page;
use BackedEnum;
use UnitEnum;
use Filament\Support\Icons\Heroicon;

class QRValidation extends Page
{
    protected string $view = 'filament.pages.q-r-validation';

    protected static string|BackedEnum|null $navigationIcon =
        Heroicon::OutlinedQrCode;

    protected static string|UnitEnum|null $navigationGroup =
        'Check-in';

    protected static ?string $navigationLabel =
        'Validar QR';

    protected static ?string $title =
        'Validar QR';

    public array $events = [];

    public function mount(): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {

            $this->events = Event::all()->toArray();

            return;
        }

        if ($user->isOrganizador()) {

            $this->events = Event::where(
                'organizer_id',
                $user->id
            )->get()->toArray();

            return;
        }

        if ($user->isStaff()) {

            $this->events = $user
                ->staffEvents
                ->toArray();

            return;
        }

        abort(403);
    }

    public static function canAccess(): bool
    {
        $user = auth()->user();

        return (
            $user?->isAdmin()
            || $user?->isOrganizador()
            || $user?->isStaff()
        );
    }
}
