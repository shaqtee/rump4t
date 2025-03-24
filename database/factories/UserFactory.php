<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'name' => fake()->name(),
            // 'email' => fake()->unique()->safeEmail(),
            // 'email_verified_at' => now(),
            // 'password' => static::$password ??= Hash::make('password'),
            // 'remember_token' => Str::random(10),
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'email_verified_at' => now(),
            'phone_verified_at' => now(),
            'password' => bcrypt('password'), // default password
            'phone' => $this->faker->phoneNumber,
            // 'otp_code' => $this->faker->randomNumber(6),
            // 'otp_expired' => now()->addMinutes(10),
            'gender' => $this->faker->randomElement(['L', 'P']),
            'birth_date' => $this->faker->date,
            'hcp_index' => $this->faker->randomFloat(2, 0, 10),
            'faculty' => $this->faker->word,
            'batch' => $this->faker->year,
            'office_name' => $this->faker->company,
            'address' => $this->faker->address,
            'business_sector' => $this->faker->word,
            'position' => $this->faker->jobTitle,
            'active' => $this->faker->randomElement([1, 0]),
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
