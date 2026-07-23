<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CouponController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if ($user->role === 'superadmin') {
            $coupons = Coupon::with('event')->get();
        } else {
            // Organizer hanya melihat kupon untuk event miliknya
            $coupons = Coupon::with('event')->whereHas('event', function ($q) use ($user) {
                $q->where('organizer_id', $user->id);
            })->get();
        }

        return view('admin.coupons.index', compact('coupons'));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->role === 'superadmin') {
            $events = Event::all();
        } else {
            $events = Event::where('organizer_id', $user->id)->get();
        }
        
        return view('admin.coupons.create', compact('events'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code',
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'event_id' => 'nullable|exists:events,id',
            'is_active' => 'boolean',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_until' => 'nullable|date',
        ]);

        $user = Auth::user();
        if ($user->role !== 'superadmin' && !$request->event_id) {
            return back()->with('error', 'Organizer harus memilih Event spesifik (tidak bisa membuat Kupon General).');
        }

        if ($request->event_id && $user->role !== 'superadmin') {
            $event = Event::findOrFail($request->event_id);
            if ($event->organizer_id !== $user->id) {
                abort(403);
            }
        }

        Coupon::create([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'event_id' => $request->event_id,
            'is_active' => $request->has('is_active') ? true : false,
            'usage_limit' => $request->usage_limit,
            'valid_until' => $request->valid_until,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil ditambahkan.');
    }

    public function edit(Coupon $coupon)
    {
        $user = Auth::user();
        
        if ($user->role !== 'superadmin') {
            if (!$coupon->event_id || $coupon->event->organizer_id !== $user->id) {
                abort(403);
            }
            $events = Event::where('organizer_id', $user->id)->get();
        } else {
            $events = Event::all();
        }

        return view('admin.coupons.edit', compact('coupon', 'events'));
    }

    public function update(Request $request, Coupon $coupon)
    {
        $request->validate([
            'code' => 'required|string|unique:coupons,code,' . $coupon->id,
            'discount_type' => 'required|in:percent,fixed',
            'discount_value' => 'required|numeric|min:0',
            'event_id' => 'nullable|exists:events,id',
            'usage_limit' => 'nullable|integer|min:1',
            'valid_until' => 'nullable|date',
        ]);

        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            if (!$coupon->event_id || $coupon->event->organizer_id !== $user->id) {
                abort(403);
            }
            if (!$request->event_id) {
                return back()->with('error', 'Organizer harus memilih Event spesifik.');
            }
            $event = Event::findOrFail($request->event_id);
            if ($event->organizer_id !== $user->id) {
                abort(403);
            }
        }

        $coupon->update([
            'code' => strtoupper($request->code),
            'discount_type' => $request->discount_type,
            'discount_value' => $request->discount_value,
            'event_id' => $request->event_id,
            'is_active' => $request->has('is_active') ? true : false,
            'usage_limit' => $request->usage_limit,
            'valid_until' => $request->valid_until,
        ]);

        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil diperbarui.');
    }

    public function destroy(Coupon $coupon)
    {
        $user = Auth::user();
        if ($user->role !== 'superadmin') {
            if (!$coupon->event_id || $coupon->event->organizer_id !== $user->id) {
                abort(403);
            }
        }

        $coupon->delete();
        return redirect()->route('admin.coupons.index')->with('success', 'Kupon berhasil dihapus.');
    }
}
