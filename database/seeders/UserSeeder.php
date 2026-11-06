<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;


class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        // ADMIN
        User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => 'ajeng',
            'role' => 'admin'
        ]);

        // OPERATOR
        User::create([
            'name' => 'Operator',
            'email' => 'operator@gmail.com',
            'password' => 'sucipto',
            'role' => 'operator'
        ]);

        // MANAGER
        User::create([
            'name' => 'Manager',
            'email' => 'manager@gmail.com',
            'password' => 'narlin',
            'role' => 'manager'
        ]);

    }
}