<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
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

        DB::table('users')->insert([
            [
                'name' => "Tommy",
                'email' => 'test1@mail.com',
                'password' => Hash::make('12345678'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => "Hans",
                'email' => 'test2@mail.com',
                'password' => Hash::make('12345678'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => "Børge",
                'email' => 'test3@mail.com',
                'password' => Hash::make('12345678'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => "Benny",
                'email' => 'test4@mail.com',
                'password' => Hash::make('12345678'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ]
        ]);
    }
}
