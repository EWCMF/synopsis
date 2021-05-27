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
                'name' => "Tester1",
                'email' => 'test1@mail.com',
                'password' => Hash::make('12345678'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => "Tester2",
                'email' => 'test2@mail.com',
                'password' => Hash::make('12345678'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => "Tester3",
                'email' => 'test3@mail.com',
                'password' => Hash::make('12345678'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ],
            [
                'name' => "Tester4",
                'email' => 'test4@mail.com',
                'password' => Hash::make('12345678'),
                'created_at' => $createdAt,
                'updated_at' => $updatedAt
            ]
        ]);
    }
}
