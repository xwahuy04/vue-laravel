<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;



class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Schema::disableForeignKeyConstraints();
        User::truncate();
        Schema::enableForeignKeyConstraints();

        DB::table('users')->insert([
            [
                'name' => 'manager',
                'email' => 'manager@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => 4,
            ],
            [
                'name' => 'chef',
                'email' => 'chef@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => 2,
            ],
            [
                'name' => 'waitress',
                'email' => 'waitress@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => 1,
            ],
            [
                'name' => 'cashier',
                'email' => 'cashier@gmail.com',
                'password' => Hash::make('12345678'),
                'role_id' => 3,
            ]
        ]);
    }
}
