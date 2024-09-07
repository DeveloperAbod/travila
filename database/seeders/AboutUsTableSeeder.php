<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AboutUsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('about_us')->insert([
            'email' => 'abod@example.com',
            'google_map_url' => 'https://maps.google.com/?q=1234+Main+St',
            'call_number' => 774370569,
            'whats_number' => 774370569,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}
// php artisan db:seed --class=aboutUsTableSeeder