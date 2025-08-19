<?php

use App\Models\User;
use Illuminate\Support\Facades\Hash;

it('throttles excessive login attempts', function () {
    $user = User::factory()->create([
        'password' => Hash::make('correct-password'),
    ]);

    // 6 wrong attempts to trigger throttle:login -> 429
    for ($i = 0; $i < 6; $i++) {
        $resp = $this->postJson('/api/login', [
            'email' => $user->email,
            'password' => 'wrong-password',
        ]);
    }

    $resp->assertStatus(429);
});
