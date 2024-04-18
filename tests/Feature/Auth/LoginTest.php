<?php

use App\Models\Admin;
use App\Models\User;

it('can login', function () {
    $profile = Admin::factory()->create();
    $user = User::factory(['email_verified_at' => now(), 'profile_id' => $profile->id, 'profile_type' => $profile::class, 'email' => $profile->email])->create();
    $response = $this->postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'Password780@',
        'device_name' => 'PC',
    ]);

    $response->assertStatus(201);
    $response->assertJsonStructure(['data']);
    expect($response->json()['message'])
        ->toBe("Login successful");
});

it('can not login with wrong credentials', function () {
    $profile = Admin::factory()->create();
    $user = User::factory(['email_verified_at' => now(), 'profile_id' => $profile->id, 'profile_type' => $profile::class, 'email' => $profile->email])->create();
    $response = $this->postJson('/api/v1/login', [
        'email' => $user->email,
        'password' => 'Password78ertrt0@',
        'device_name' => 'PC',
    ]);
    $response->assertStatus(400);
    expect($response->json()['message'])
        ->toBe("The provided credentials are incorrect.");
});
