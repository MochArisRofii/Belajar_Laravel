<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AkunSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = [
            [
                'username' => 'admin',
                'name' => 'administrator',
                'email' => 'admin@gmail.com',
                'level' => 'admin',
                'password' => bcrypt('admin'),
            ],
            [
                'username' => 'pelanggan',
                'name' => 'pelanggan',
                'email' => 'pelanggan@gmail.com',
                'level' => 'customer',
                'password' => bcrypt('123456'),
            ]
        ];
        foreach ($user as $key => $value) {
            User::create($value);
        }
    }
}
