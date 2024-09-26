<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        if (!Role::where('name', 'admin')->exists()) {
           
    
        Role::create([
            'name' => 'admin',
            'guard_name' => 'api',
            'full_name' => 'Administrator',
        ]);

    }

         // Role untuk pengguna biasa
         Role::create([
            'name' => 'user',
            'guard_name' => 'api',
            'full_name' => 'User Biasa',
         ]);
    }
    
}
