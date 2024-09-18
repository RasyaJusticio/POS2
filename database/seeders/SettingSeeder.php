<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('settings')->truncate();

        Setting::create([
            'app' => 'AON Cashier',
            'description' =>  'Aplikasi AON Cashier',
            'logo' =>  '/LOGO.png',
            'bg_auth' =>  '/media/misc/bg-auth.jpg',
            'banner' =>  '/media/misc/banner.jpg',
            'pemerintah' =>  'Pemerintah Provinsi Jawa Timur',
            //'dinas' =>  'Dinas Lingkungan Hidup',
            'alamat' =>  '',
            'telepon' =>  '',
            'email' =>  '',
        ]);
    }
}
