<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'Admin',
            'designation' => 'Administrator',
            'username' => 'admin',
            'email' => 'admin@gmail.com',
            'is_admin' => 1,
            'password' => Hash::make('admin@123'),
        ]);
    }
}
