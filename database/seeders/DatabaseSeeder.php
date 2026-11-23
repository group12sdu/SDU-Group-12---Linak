<?php

namespace Database\Seeders;

use App\Models\CleaningSetting;
use App\Models\Desk;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Demo accounts. Password for all of them: "password" (change before real use!)
        $users = [
            ['name' => 'Admin',        'username' => 'admin',   'role' => 'admin',    'standing_height' => 110, 'sitting_height' => 72],
            ['name' => 'Daniel Falat', 'username' => 'daniel',  'role' => 'employee', 'standing_height' => 112, 'sitting_height' => 74],
            ['name' => 'Dominik Bezo', 'username' => 'dominik', 'role' => 'employee', 'standing_height' => 118, 'sitting_height' => 78],
        ];
        foreach ($users as $user) {
            User::updateOrCreate(['username' => $user['username']], $user + ['password' => 'password']);
        }

        foreach (range(1, 6) as $n) {
            Desk::firstOrCreate(
                ['building' => 1, 'floor' => 1, 'room' => 101, 'desk_number' => $n],
                ['height' => 72, 'mode' => 'Sitting'],
            );
        }

        CleaningSetting::current();
    }
}