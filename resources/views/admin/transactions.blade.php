@extends('layouts.admin')

@section('content')
    <div class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black">Laporan Transaksi</h1>
            <p class="text-slate-500 font-medium">Pantau arus kas dan penjualan tiket Anda.</p>
        </div>
        <div class="flex gap-4">
            <button class="px-6 py-3 border-2 border-slate-200 rounded-2xl font-bold hover:bg-white hover:border-indigo-600 hover:text-indigo-600 transition">
                Ekspor Excel
            </button>
            <button class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg hover:bg-indigo-700 transition">
                Unduh PDF
            </button>
        </div>
    </div>

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        
        {{-- Filter & Search Area --}}
        <form action="{{ route('admin.transactions.index') }}" method="GET" class="px-8 py-6 bg-slate-50/50 border-b flex flex-wrap gap-4 items-center">
            <div class="flex-1 min-w-[300px] flex gap-2">
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Order ID, Nama, atau Email..."
                    class="flex-1 px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition uppercase text-sm font-medium tracking-wide">
                <button type="submit" class="px-5 py-3 bg-indigo-600 text-white rounded-xl font-bold shadow-md hover:bg-indigo-700 transition">
                    Cari
                </button>
            </div>
            <div class="flex gap-2">
                <select name="status" onchange="this.form.submit()" class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold">
                    <option value="">Semua Status</option>
                    <option value="SUCCESS" {{ request('status') == 'SUCCESS' ? 'selected' : '' }} class="text-green-600">Success</option>
                    <option value="PENDING" {{ request('status') == 'PENDING' ? 'selected' : '' }} class="text-orange-600">Pending</option>
                    <option value="EXPIRED" {{ request('status') == 'EXPIRED' ? 'selected' : '' }} class="text-rose-600">Expired</option>
                </select>
            </div>
        </form>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Order ID</th>
                        <th class="px-8 py-4">Detail Pembeli</th>
                        <th class="px-8 py-4">Event</th>
                        <th class="px-8 py-4">Tgl Transaksi</th>
                        <th class="px-8 py-4">Status</th>
                        <th class="px-8 py-4 text-right">Total Tagihan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-50">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-6 font-bold text-indigo-600 text-xs tracking-wider">
                                #{{ $trx->order_id }}
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-black text-slate-800">{{ $trx->customer_name }}</p>
                                <p class="text-xs text-slate-400">{{ $trx->customer_email }}</p>
                            </td>
                            <td class="px-8 py-6">
                                <p class="font-bold text-slate-700 text-sm">{{ $trx->event_name }}</p>
                            </td>
                            <td class="px-8 py-6 text-sm text-slate-500 font-medium">
                                {{ $trx->created_at->format('d M Y, H:i') }}
                            </td>
                            <td class="px-8 py-6">
                                @if($trx->status == 'SUCCESS')
                                    <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[10px] font-black uppercase tracking-widest">Success</span>
                                @elseif($trx->status == 'PENDING')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-[10px] font-black uppercase tracking-widest">Pending</span>
                                @else
                                    <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-full text-[10px] font-black uppercase tracking-widest">Expired</span>
                                @endif
                            </td>
                            <td class="px-8 py-6 text-right font-black text-slate-900">
                                Rp {{ number_format($trx->total, 0, ',', '.') }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="px-8 py-20 text-center">
                                <div class="flex flex-col items-center">
                                    <p class="text-slate-400 font-bold">Tidak ada transaksi ditemukan.</p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination Area --}}
        <div class="px-8 py-6 bg-slate-50/50 border-t flex justify-between items-center">
            <p class="text-sm text-slate-500 font-medium">
                Menampilkan {{ $transactions->firstItem() ?? 0 }} sampai {{ $transactions->lastItem() ?? 0 }} dari {{ $transactions->total() }} transaksi
            </p>
            <div class="flex gap-2">
                {{ $transactions->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection