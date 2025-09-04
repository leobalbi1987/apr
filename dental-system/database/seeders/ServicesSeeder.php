<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('services')->insert([
            [
                'service_offer' => 'Tooth Extraction',
                'price' => 800.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_offer' => 'Tooth Restoration',
                'price' => 1500.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_offer' => 'Teeth Cleaning',
                'price' => 500.00,
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'service_offer' => 'Mouth Prophylaxis',
                'price' => 600.00,
                'created_at' => now(),
                'updated_at' => now()
            ]
        ]);
    }
}
