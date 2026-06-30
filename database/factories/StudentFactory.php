<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class StudentFactory extends Factory
{
    public function definition(): array
    {
        return [
            'name'              => fake()->name(),
            'email'             => fake()->unique()->userName() . '@strathmore.edu',
            'email_verified_at' => now(),
            'password_hash'     => Hash::make('password'),
            'role'              => 'student',
            'student_number'    => fake()->unique()->numerify('######'),
            'school'            => 'SCES',
            'programme'         => 'BBIT',
            'year_of_study'     => fake()->numberBetween(1, 4),
            'status'            => 'active',
        ];
    }
}