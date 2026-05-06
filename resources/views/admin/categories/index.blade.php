@extends('layouts.admin')

@section('content')
<div class="p-6 sm:p-10 space-y-6">
    <div class="flex flex-col space-y-6 md:space-y-0 md:flex-row justify-between">
        <div class="mr-6">
            <h1 class="text-4xl font-semibold mb-2 text-slate-800">Manajemen Kategori</h1>
            <h2 class="text-slate-500">Kelola kategori event seperti Seminar, Konser, atau Workshop.</h2>
        </div>
        <div class="flex flex-wrap items-start justify-end -mb-3">
            <button class="inline-flex px-5 py-3 text-white bg-indigo-600 hover:bg-indigo-700 rounded-xl font-medium shadow-sm transition-colors">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Kategori
            </button>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full whitespace-nowrap">
                <thead class="bg-slate-50 border-b border-slate-200 text-slate-500 text-left text-sm font-semibold uppercase tracking-wider">
                    <tr>
                        <th class="px-6 py-4">No</th>
                        <th class="px-6 py-4">Nama Kategori</th>
                        <th class="px-6 py-4">Deskripsi Singkat</th>
                        <th class="px-6 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-200 text-slate-700">
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium">1</td>
                        <td class="px-6 py-4 font-bold text-slate-900">Seminar</td>
                        <td class="px-6 py-4 text-slate-500">Event akademik, edukasi, dan diskusi panel.</td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg text-sm font-semibold transition">Edit</button>
                            <button class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1.5 rounded-lg text-sm font-semibold transition">Hapus</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium">2</td>
                        <td class="px-6 py-4 font-bold text-slate-900">Konser</td>
                        <td class="px-6 py-4 text-slate-500">Pertunjukan musik, hiburan, dan festival.</td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg text-sm font-semibold transition">Edit</button>
                            <button class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1.5 rounded-lg text-sm font-semibold transition">Hapus</button>
                        </td>
                    </tr>
                    <tr class="hover:bg-slate-50 transition-colors">
                        <td class="px-6 py-4 font-medium">3</td>
                        <td class="px-6 py-4 font-bold text-slate-900">Workshop</td>
                        <td class="px-6 py-4 text-slate-500">Pelatihan praktis dan pengembangan *skill*.</td>
                        <td class="px-6 py-4 flex justify-center gap-3">
                            <button class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1.5 rounded-lg text-sm font-semibold transition">Edit</button>
                            <button class="text-red-600 hover:text-red-900 bg-red-50 px-3 py-1.5 rounded-lg text-sm font-semibold transition">Hapus</button>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection