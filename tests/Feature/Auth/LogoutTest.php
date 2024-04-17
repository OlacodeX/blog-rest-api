<?php

use App\Models\Admin;
use App\Models\User;

it('can logout', function () {
    $profile = Admin::factory()->create();
    $user = User::factory(['email_verified_at' => now(), 'profile_id' => $profile->id, 'profile_type' => $profile::class, 'email' => $profile->email])->create();
    $response = $this->postJson('/api/v1/logout', [
        'email' => $user->email,
    ]);

    $response->assertStatus(200);
    expect($response->json())->toEqual([]);
});
