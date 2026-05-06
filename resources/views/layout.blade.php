<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <!-- Navbar -->
    <nav class="bg-white shadow-md p-4 flex gap-4 justify-center">
        <a href="/" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Home</a>
        <a href="/profil" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Profil</a>
        <a href="/katalog" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Katalog</a>
        <a href="/bantuan" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Bantuan</a>
        <a href="/kontak" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600">Kontak</a>
    </nav>

    <div class="container mx-auto p-6">
        @yield('content')
    </div>

</body>
</html>