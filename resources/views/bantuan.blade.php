@extends('layout')

@section('title', 'Bantuan / FAQ')

@section('content')
<h1 class="text-2xl font-bold mb-4 text-center">FAQ - Bantuan</h1>
<div class="bg-white p-6 rounded-lg shadow-md max-w-2xl mx-auto">
    <ul class="space-y-4">
        <li>
            <strong>Apa itu AmikomEventHub?</strong>
            <p class="text-gray-600">AmikomEventHub adalah platform untuk melihat dan mendaftar event di Amikom.</p>
        </li>
        <li>
            <strong>Bagaimana cara mendaftar event?</strong>
            <p class="text-gray-600">Klik tombol "Detail" pada event dan ikuti petunjuk pendaftaran.</p>
        </li>
        <li>
            <strong>Apakah ada biaya pendaftaran?</strong>
            <p class="text-gray-600">Beberapa event gratis, beberapa berbayar. Informasi lengkap ada di halaman detail event.</p>
        </li>
    </ul>
</div>
@endsection