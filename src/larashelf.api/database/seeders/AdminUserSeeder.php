<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email    = env('ADMIN_EMAIL');
        $password = env('ADMIN_PASSWORD');

        if (blank($email) || blank($password)) {
            $this->command?->warn('AdminUserSeeder: no ADMIN_EMAIL or ADMIN_PASSWORD set — skipping.');
            return;
        }

        $user = User::updateOrCreate(['email' => $email], [
            'first_name' => env('ADMIN_FIRST_NAME'),
            'last_name'  => env('ADMIN_LAST_NAME'),
            'username'   => env('ADMIN_USERNAME'),
            'password'   => $password,
        ]);
    }
}
