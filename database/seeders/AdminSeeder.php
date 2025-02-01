<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('groups')->insert([
            'nama_group' => 'Admin123',
            'password_group' => Hash::make('password123'),
            'nama_leader' => 'Admin123',
            'email_leader' => 'admin123@gmail.com',
            'nomor_wa_leader' => '081311332436',
            'id_line_leader' => 'adminLine',
            'github_leader' => 'AdminGit',
            'tmp_lahir_leader' => 'Jakarta',
            'tgl_lahir_leader' => '2001-01-01',
            'is_admin' => 1,
            'is_binusian' => 'binusian',
            'cv' => null,
            'flazz_card' => null,
            'id_card' => null,
            'remember_token' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
}