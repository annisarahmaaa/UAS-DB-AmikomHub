@extends('layouts.admin')

@section('title', 'Kelola Kupon Diskon')

@section('content')
<div class="mb-8 flex justify-between items-end">
    <div>
        <h1 class="text-3xl font-bold text-slate-800">Manajemen Kupon</h1>
        <p class="text-slate-500 mt-1">Buat dan atur kode promo untuk mendongkrak penjualan.</p>
    </div>
    <a href="{{ route('admin.coupons.create') }}" class="bg-indigo-600 hover:bg-indigo-700 text-white px-6 py-3 rounded-xl font-medium transition-all shadow-lg shadow-indigo-200 flex items-center gap-2">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
        Buat Kupon Baru
    </a>
</div>

@if(session('success'))
<div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-xl font-bold">
    {{ session('success') }}
</div>
@endif

@if(session('error'))
<div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
    {{ session('error') }}
</div>
@endif

<div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
    <div class="overflow-x-auto">
        <table class="w-full text-left">
            <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 uppercase text-xs font-bold tracking-wider">
                <tr>
                    <th class="px-6 py-4">Kode Kupon</th>
                    <th class="px-6 py-4">Tipe & Nilai Diskon</th>
                    <th class="px-6 py-4">Berlaku Untuk</th>
                    <th class="px-6 py-4">Kuota Penggunaan</th>
                    <th class="px-6 py-4">Batas Waktu</th>
                    <th class="px-6 py-4">Status</th>
                    <th class="px-6 py-4 text-right">Aksi</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-slate-100">
                @forelse($coupons as $coupon)
                <tr class="hover:bg-slate-50 transition-colors">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <span class="font-bold text-lg text-slate-800 tracking-widest">{{ $coupon->code }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($coupon->discount_type === 'percent')
                            <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full font-bold text-sm">{{ $coupon->discount_value }}% OFF</span>
                        @else
                            <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full font-bold text-sm">Rp {{ number_format($coupon->discount_value, 0, ',', '.') }}</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($coupon->event_id)
                            <span class="text-indigo-600 font-bold text-sm">{{ $coupon->event->title }}</span>
                        @else
                            <span class="px-3 py-1 bg-slate-200 text-slate-700 rounded-full font-bold text-xs uppercase">General (Semua Event)</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->usage_limit)
                            <div class="flex items-center gap-2">
                                <span class="text-sm font-bold {{ $coupon->used_count >= $coupon->usage_limit ? 'text-rose-600' : 'text-slate-700' }}">{{ $coupon->used_count }} / {{ $coupon->usage_limit }}</span>
                                <div class="w-16 h-2 bg-slate-200 rounded-full overflow-hidden">
                                    @php $percent = min(100, ($coupon->used_count / $coupon->usage_limit) * 100); @endphp
                                    <div class="h-full {{ $percent >= 100 ? 'bg-rose-500' : 'bg-indigo-500' }}" style="width: {{ $percent }}%"></div>
                                </div>
                            </div>
                        @else
                            <span class="text-xs text-slate-500 italic">Tanpa Batas</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->valid_until)
                            <span class="text-sm font-bold {{ $coupon->valid_until->isPast() ? 'text-rose-600' : 'text-slate-700' }}">
                                {{ $coupon->valid_until->format('d M Y, H:i') }}
                                @if($coupon->valid_until->isPast())
                                    <br><span class="text-xs font-normal">(Kedaluwarsa)</span>
                                @endif
                            </span>
                        @else
                            <span class="text-xs text-slate-500 italic">Selamanya</span>
                        @endif
                    </td>
                    <td class="px-6 py-4">
                        @if($coupon->isValid())
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-emerald-100 text-emerald-700 rounded-full text-xs font-bold">
                                <span class="w-1.5 h-1.5 bg-emerald-500 rounded-full"></span> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1.5 px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-xs font-bold">
                                <span class="w-1.5 h-1.5 bg-rose-500 rounded-full"></span> Tidak Aktif/Habis
                            </span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-right">
                        <div class="flex items-center justify-end gap-3">
                            <a href="{{ route('admin.coupons.edit', $coupon->id) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">Edit</a>
                            
                            <form action="{{ route('admin.coupons.destroy', $coupon->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus kupon ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-rose-500 hover:text-rose-700 font-medium">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="5" class="px-6 py-12 text-center text-slate-500">
                        Belum ada kupon diskon yang dibuat.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
