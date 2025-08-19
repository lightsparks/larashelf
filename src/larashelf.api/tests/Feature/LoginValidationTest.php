<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('rejects login with missing fields', function () {
    $this->postJson('/api/login', [])->assertStatus(422)
         ->assertJsonValidationErrors(['identifier', 'password']);
});

it('rejects login with invalid *email* format when identifier contains @', function () {
    // looks like an email, so strict email validation must fire
    $this->postJson('/api/login', [
        'identifier' => 'not-an-email',
        'password'   => 'x',
    ])->assertStatus(422)
         ->assertJsonValidationErrors(['identifier']); // email rule violation
});

it('rejects login with incorrect password (username path)', function () {
    $user = User::factory()->create([
        'username' => 'johnny',
        'password' => Hash::make('correct-password'),
    ]);

    $this->postJson('/api/login', [
        'identifier' => 'johnny',       // username path
        'password'   => 'wrong-password',
    ])->assertStatus(422)
         ->assertJsonValidationErrors(['identifier']); // incorrect credentials
});
