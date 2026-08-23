<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Category;
class EventController extends Controller
{
    public function index(Request $request)
{
    $query = Event::query()
        ->where('status', 'active')
        ->where('visibility', 'public');

    // 🔎 PESQUISA
    if ($request->search) {

        $query->where(function ($q) use ($request) {

            $q->where('title', 'like', '%' . $request->search . '%')
              ->orWhere('description', 'like', '%' . $request->search . '%');

        });
    }

    // 📍 LOCALIZAÇÃO
    if ($request->location) {

        $query->where('city', 'like', '%' . $request->location . '%');

    }

    $events = $query
        ->with('category')
        ->orderBy('date', 'asc')
        ->paginate(12);

    return view('events.index', compact('events'));
}

public function category(Category $category)
{
    $events = $category->events()
        ->where('status', 'active')
        ->where('visibility', 'public')
        ->with('category')
        ->orderBy('date', 'asc')
        ->paginate(12);

    return view('events.index', compact('events'));
}

public function show(Event $event)
{
    $event->load([
        'category',
        'eventArtists.artist',
        'images',
        'ticketTypes'
    ]);

   $relatedEvents = Event::where('category_id', $event->category_id)
        ->where('id', '!=', $event->id)
        ->where('status', 'active')
        ->where('visibility', 'public')
        ->orderBy('date', 'asc')
        ->limit(3)
        ->get();

    return view('events.show', compact('event', 'relatedEvents'));
}

public function register(Request $request, Event $event)
{
    $user = Auth::user();

    // 1. VALIDAR SEAT
    $request->validate([
        'seat_id' => 'required|exists:seats,id',
    ]);

    $seat = $event->seats()
        ->where('id', $request->seat_id)
        ->firstOrFail();

    // 2. VERIFICAR SE JÁ ESTÁ OCUPADO
    if ($seat->status !== 'available') {
        return back()->with('error', 'Este lugar já foi ocupado.');
    }

    // 3. VERIFICAR SE JÁ ESTÁ INSCRITO NO EVENTO
    $alreadyRegistered = Registration::where('user_id', $user->id)
        ->where('event_id', $event->id)
        ->exists();

    if ($alreadyRegistered) {
        return back()->with('error', 'Já está inscrito neste evento.');
    }

    // 4. VERIFICAR CAPACIDADE
    if ($event->registrations()->count() >= $event->capacity) {
        return back()->with('error', 'Evento esgotado.');
    }

    // 5. CRIAR INSCRIÇÃO
    Registration::create([
        'user_id' => $user->id,
        'event_id' => $event->id,
        'seat_id' => $seat->id,
        'status' => 'confirmed',
        'ticket_token' => Str::uuid()->toString(),
    ]);

    // 6. MARCAR LUGAR COMO OCUPADO
    $seat->update([
        'status' => 'sold',
    ]);

    return redirect()->route('registrations.my')
        ->with('success', 'Inscrição com lugar marcada! O seu QR Code já está disponível.');
}


public function private($token)
{
    $event = Event::where('private_token', $token)
        ->where('visibility', 'private')
        ->where('status', 'active')
        ->firstOrFail();

    $event->load('category');

    $relatedEvents = Event::where('category_id', $event->category_id)
        ->where('id', '!=', $event->id)
        ->where('status', 'active')
        ->where('visibility', 'public')
        ->with('category')
        ->limit(3)
        ->get();

    return view('events.show', compact('event', 'relatedEvents'));
}

}
