<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\TicketTier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketTierController extends Controller
{
    private function checkAccess(Event $event)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin' && $event->organizer_id !== $user->id) {
            abort(403);
        }
    }

    public function index(Event $event)
    {
        $this->checkAccess($event);
        $tiers = $event->ticketTiers()->orderBy('start_date')->get();
        return view('admin.tiers.index', compact('event', 'tiers'));
    }

    public function create(Event $event)
    {
        $this->checkAccess($event);
        return view('admin.tiers.create', compact('event'));
    }

    public function store(Request $request, Event $event)
    {
        $this->checkAccess($event);

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $event->ticketTiers()->create([
            'name' => $request->name,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.events.tiers.index', $event->id)
                         ->with('success', 'Rentang harga berhasil ditambahkan.');
    }

    public function edit(Event $event, TicketTier $tier)
    {
        $this->checkAccess($event);
        
        if ($tier->event_id !== $event->id) {
            abort(404);
        }

        return view('admin.tiers.edit', compact('event', 'tier'));
    }

    public function update(Request $request, Event $event, TicketTier $tier)
    {
        $this->checkAccess($event);

        if ($tier->event_id !== $event->id) {
            abort(404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
        ]);

        $tier->update([
            'name' => $request->name,
            'price' => $request->price,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('admin.events.tiers.index', $event->id)
                         ->with('success', 'Rentang harga berhasil diperbarui.');
    }

    public function destroy(Event $event, TicketTier $tier)
    {
        $this->checkAccess($event);

        if ($tier->event_id !== $event->id) {
            abort(404);
        }

        $tier->delete();
        return redirect()->route('admin.events.tiers.index', $event->id)
                         ->with('success', 'Rentang harga berhasil dihapus.');
    }
}
