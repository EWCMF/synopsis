<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CardTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $createdAt = Carbon::now();
        $updatedAt = Carbon::now();

        DB::table('card_types')->insert([
            [
                'name' => 'Plot',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Population',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Technology',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Unit',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Building',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Wonder',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => 'Bonus resource',
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ]
        ]);
    }
}
