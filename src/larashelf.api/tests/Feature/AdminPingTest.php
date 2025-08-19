<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('allows admin to hit /api/admin/ping', function () {
    $admin = User::factory()->admin()->create([
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($admin)
         ->getJson('/api/admin/ping')
         ->assertOk()
         ->assertJson(['ok' => true]);
});

it('forbids non-admin on /api/admin/ping', function () {
    $user = User::factory()->create([
        'password' => bcrypt('password'),
    ]);

    $this->actingAs($user)
         ->getJson('/api/admin/ping')
         ->assertForbidden();
});
