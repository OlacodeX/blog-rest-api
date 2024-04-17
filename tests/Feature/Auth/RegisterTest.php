<?php

use Illuminate\Support\Arr;

it('can register', function () {
    $email = fake()->unique()->safeEmail();

    $response = $this->postJson('/api/v1/register', [
        'email' => $email,
        'name' => fake()->name(),
        'user_type' => 'Admin',
        'password' => 'Password690@',
        'password_confirmation' => 'Password690@',
    ]);
    $response->assertStatus(201);
    expect($response->json())
        ->toBe('Registration successful. Kindly check your inbox for instructions on how to verify your account. Thanks.');
});

