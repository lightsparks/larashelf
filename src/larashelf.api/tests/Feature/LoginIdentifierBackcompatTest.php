<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('accepts legacy "email" key as identifier for username login (back-compat)', function () {
    $user = User::factory()->create([
        'username' => 'legacy_user',
        'email'    => 'legacy@example.test',
        'password' => Hash::make('topsecret'),
    ]);

    $this->postJson('/api/login', [
        'email'    => 'legacy_user',   // intentionally sending username in "email" field
        'password' => 'topsecret',
    ])->assertOk()
         ->assertJsonPath('user.username', 'legacy_user');

    $this->getJson('/api/me')
         ->assertOk()
         ->assertJsonPath('username', 'legacy_user');
});
