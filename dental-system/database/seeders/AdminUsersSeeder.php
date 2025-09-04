<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminUsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Usar updateOrInsert para evitar duplicatas
        DB::table('admin_users')->updateOrInsert(
            ['username' => 'admin'],
            [
                'password' => Hash::make('admin'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
        
        DB::table('admin_users')->updateOrInsert(
            ['username' => 'john'],
            [
                'password' => Hash::make('john'),
                'created_at' => now(),
                'updated_at' => now()
            ]
        );
    }
}
