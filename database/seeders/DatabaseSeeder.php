<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Category;
use App\Models\Event;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // 1. Akun Admin Utama
        User::firstOrCreate(
            ['email' => 'admin@amikom.ac.id'],
            [
                'name'     => 'Admin Amikom',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        // 2. Insert 3 Kategori Event
        $catTech = Category::firstOrCreate(
            ['slug' => 'teknologi-desain'], 
            ['name' => 'Teknologi & Desain']
        );
        $catSport = Category::firstOrCreate(
            ['slug' => 'olahraga-esport'], 
            ['name' => 'Olahraga & E-Sport']
        );
        $catArt = Category::firstOrCreate(
            ['slug' => 'seni-hiburan'], 
            ['name' => 'Seni & Hiburan']
        );

        // 3. Insert 6 Sampel Events
        // Event 1
        Event::create([
            'category_id' => $catTech->id,
            'title'       => 'UI/UX Masterclass: Designing for the Future',
            'description' => 'Pelajari tren UI/UX terbaru dan tingkatkan skill desainmu bersama expert dari industri.',
            'date'        => '2026-06-15 09:00:00',
            'location'    => 'Lab Komputer 1',
            'price'       => 75000,
            'stock'       => 50,
            'poster_path' => 'posters/uiux.png',
        ]);

        // Event 2
        Event::create([
            'category_id' => $catTech->id,
            'title'       => 'Web3 & Blockchain Development Workshop',
            'description' => 'Workshop intensif membangun aplikasi desentralisasi (DApps) menggunakan teknologi Web3.',
            'date'        => '2026-06-20 10:00:00',
            'location'    => 'Ruang Citra 2',
            'price'       => 150000,
            'stock'       => 30,
            'poster_path' => 'posters/web3.png',
        ]);

        // Event 3
        Event::create([
            'category_id' => $catSport->id,
            'title'       => 'E-Sport U-Champ: Mobile Legends',
            'description' => 'Turnamen Mobile Legends antar mahasiswa terbesar tahun ini.',
            'date'        => '2026-07-05 13:00:00',
            'location'    => 'Auditorium Kampus',
            'price'       => 100000,
            'stock'       => 64,
            'poster_path' => 'posters/mlbb.png',
        ]);

        // Event 4
        Event::create([
            'category_id' => $catSport->id,
            'title'       => 'Amikom Fun Run 5K 2026',
            'description' => 'Ayo lari sehat mengelilingi area kampus bersama teman-teman mahasiswa lainnya.',
            'date'        => '2026-07-12 06:00:00',
            'location'    => 'Halaman Depan Kampus',
            'price'       => 35000,
            'stock'       => 200,
            'poster_path' => 'posters/funrun.png',
        ]);

        // Event 5
        Event::create([
            'category_id' => $catArt->id,
            'title'       => 'Standup Comedy Campus Tour',
            'description' => 'Malam penuh tawa bersama komika-komika lokal kampus dan nasional.',
            'date'        => '2026-08-01 19:00:00',
            'location'    => 'Student Center',
            'price'       => 50000,
            'stock'       => 150,
            'poster_path' => 'posters/standup.png',
        ]);

        // Event 6
        Event::create([
            'category_id' => $catArt->id,
            'title'       => 'Indie Music Fest 2026',
            'description' => 'Festival musik yang menampilkan band-band indie lokal kampus yang berbakat.',
            'date'        => '2026-08-15 16:00:00',
            'location'    => 'Lapangan Utama',
            'price'       => 60000,
            'stock'       => 300,
            'poster_path' => 'posters/musicfest.png',
        ]);

        // 4. Memanggil Seeder Partner (Tugas 2 - Soal Responsi)
        $this->call([
            PartnerSeeder::class,
        ]);
    }
}