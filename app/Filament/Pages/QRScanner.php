<?php

namespace App\Filament\Pages;

use App\Models\Event;
use Filament\Pages\Page;

class QRScanner extends Page
{
    protected string $view = 'filament.pages.q-r-scanner';
    protected static ?string $slug = 'qr-scanner/{event}';
    protected static bool $shouldRegisterNavigation = false;

    public Event $event;

    public function mount(Event $event): void
    {
        $user = auth()->user();

        if ($user->isAdmin()) {
            $this->event = $event;
            return;
        }

        if (
            $user->isOrganizador()
            && $event->organizer_id === $user->id
        ) {
            $this->event = $event;
            return;
        }

        if (
            $user->isStaff()
            && $user->staffEvents->contains($event->id)
        ) {
            $this->event = $event;
            return;
        }

        abort(403);
    }
}
