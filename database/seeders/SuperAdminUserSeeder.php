<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class SuperAdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('users')->insert([
            [
                'name' => 'Super Admin',
                'phone' => '',
                'email' => 'admin123@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('admindemo'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Admin PT',
                'phone' => '',
                'email' => 'admin@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'User PT',
                'phone' => '',
                'email' => 'user@gmail.com',
                'email_verified_at' => now(),
                'password' => Hash::make('12345678'),
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('roles')->insert([
            [
                'nama_role' => 'Super Admin',
                'kode_role' => 'superadmin',
                'user_id'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Admin',
                'kode_role' => 'admin',
                'user_id'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'User',
                'kode_role' => 'user',
                'user_id'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_role' => 'Customer',
                'kode_role' => 'customer',
                'user_id'   => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('privilages')->insert([
            [
                'role_id' => 1,
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 2,
                'user_id' => 2,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'role_id' => 3,
                'user_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);

        DB::table('status_booking')->insert([
            [
                'nama_status' => 'Available',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'nama_status' => 'Not Available',
                'user_id' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
