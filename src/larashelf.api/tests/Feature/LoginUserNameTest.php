<?php


use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('logs in with username + password', function () {
    $user = User::factory()->create([
        'username' => 'demo_user_123',
        'email' => 'demo@example.test',
        'password' => Hash::make('s3cret!'),
    ]);

    $this->postJson('/api/login', [
        'identifier' => 'demo_user_123',
        'password' => 's3cret!',
    ])->assertOk()
         ->assertJsonPath('user.username', 'demo_user_123');

    $this->getJson('/api/me')
         ->assertOk()
         ->assertJsonPath('username', 'demo_user_123');
});
