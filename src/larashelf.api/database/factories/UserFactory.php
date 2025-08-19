<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    public function definition(): array
    {
        $first = $this->faker->firstName();
        $last  = $this->faker->lastName();

        return [
            'first_name'         => $first,
            'last_name'          => $last,
            'username'           => $this->faker->unique()->userName(),
            'email'              => $this->faker->unique()->safeEmail(),
            'email_verified_at'  => now(),
            'password'           => bcrypt('password'), // override in tests when needed
            'remember_token'     => Str::random(10),
            'is_admin'           => false,
        ];
    }

    /** Optional: easy admin state for tests */
    public function admin(): static
    {
        return $this->state(fn () => ['is_admin' => true]);
    }
}
