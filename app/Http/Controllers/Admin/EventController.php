<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <-- TAMBAHAN: Untuk mengecek user yang sedang login

class EventController extends Controller
{
    /**
     * Menampilkan daftar event (MULTI-TENANT ISOLATION)
     */
    public function index()
    {
        $user = Auth::user();

        // Jika yang login adalah 'superadmin', tampilkan semua event di platform
        if ($user->role === 'superadmin') {
            $events = Event::with('category')->latest()->paginate(10);
        } else {
            // Jika yang login adalah 'organizer', HANYA tampilkan event milik dia sendiri!
            $events = Event::with('category')
                        ->where('organizer_id', $user->id)
                        ->latest()
                        ->paginate(10);
        }

        return view('admin.events.index', compact('events'));
    }

    /**
     * Menampilkan form tambah event
     */
    public function create()
    {
        $categories = Category::all();
        return view('admin.events.create', compact('categories'));
    }

    /**
     * Menyimpan event baru (MULTI-TENANT AUTOMATION)
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('poster')) {
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        // --- TAMBAHAN MULTI-TENANT ---
        // Otomatis masukkan ID User yang sedang login sebagai pembuat (organizer) event ini
        $data['organizer_id'] = Auth::id();

        Event::create($data);

        return redirect()->route('admin.events.index')->with('success', 'Data Event berhasil ditambahkan.');
    }

    /**
     * Menampilkan form edit event (DENGAN PROTEKSI AKSES)
     */
    public function edit(Event $event)
    {
        $this->authorizeAccess($event); // Satpam penjaga tenant

        $categories = Category::all();
        return view('admin.events.edit', compact('event', 'categories'));
    }

    /**
     * Memperbarui data event (DENGAN PROTEKSI AKSES)
     */
    public function update(Request $request, Event $event)
    {
        $this->authorizeAccess($event); // Satpam penjaga tenant

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title'       => 'required|string|max:255',
            'description' => 'nullable|string',
            'date'        => 'required|date',
            'location'    => 'required|string|max:255',
            'price'       => 'required|numeric|min:0',
            'stock'       => 'required|numeric|min:1',
            'poster'      => 'nullable|image|max:2048'
        ]); 

        if ($request->hasFile('poster')) {
            if ($event->poster_path) {
                Storage::disk('public')->delete($event->poster_path);
            }
            $data['poster_path'] = $request->file('poster')->store('posters', 'public');
        }

        $event->update($data);
        
        return redirect()->route('admin.events.index')->with('success', 'Event berhasil diperbarui.');
    }

    /**
     * Menghapus data event (DENGAN PROTEKSI AKSES)
     */
    public function destroy(Event $event)
    {
        $this->authorizeAccess($event); // Satpam penjaga tenant

        if ($event->poster_path) {
            Storage::disk('public')->delete($event->poster_path);
        }

        $event->delete();

        return redirect()->route('admin.events.index')->with('success', 'Data event berhasil dihapus secara permanen.');
    }

    /**
     * FUNGSI BANTUAN (SATPAM PENJAGA TENANT)
     * Mencegah Organizer A mengedit/menghapus event milik Organizer B lewat URL
     */
    private function authorizeAccess(Event $event)
    {
        $user = Auth::user();
        // Jika dia bukan superadmin DAN bukan pemilik event tersebut, blokir aksesnya (Error 403)
        if ($user->role !== 'superadmin' && $event->organizer_id !== $user->id) {
            abort(403, 'Akses Ditolak! Kamu tidak memiliki hak atas event dari penyelenggara lain.');
        }
    }
}