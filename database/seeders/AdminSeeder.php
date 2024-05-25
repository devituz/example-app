<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

    class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        DB::table('admins')->insert([
            'name' => 'Shohbozbek',
            'last_name' => 'Turgunov',
            'phone_number' => '+998971712402',
            'password' => Hash::make('shohbozbek2402'),
            'avatarimg' => 'default.png',
        ]);
    }
}
