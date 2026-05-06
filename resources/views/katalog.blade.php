@extends('layout')

@section('title', 'Daftar Event AmikomEventHub')

@section('content')
<h1 class="text-2xl font-bold mb-4 text-center">Daftar Event AmikomEventHub</h1>
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
    @for ($i = 1; $i <= 6; $i++)
    <div class="bg-white p-4 rounded-lg shadow hover:shadow-lg transition">
        <h2 class="font-semibold text-lg mb-2">Event {{ $i }}</h2>
        <p class="text-gray-600">Deskripsi singkat tentang event {{ $i }} di AmikomEventHub.</p>
        <a href="#" class="mt-2 inline-block px-3 py-1 bg-blue-500 text-white rounded hover:bg-blue-600">Detail</a>
    </div>
    @endfor
</div>
@endsection