<?php

use App\Models\User;
use App\Models\Verify;
use App\Models\Visitor;

it('can send reset password email', function () {
    $profile = Visitor::factory()->create();
    $user = User::factory(['email_verified_at' => now(), 'profile_id' => $profile->id, 'profile_type' => $profile::class, 'email' => $profile->email])->create();
    $response = $this->postJson('/api/v1/resetpassword', [
        'email' => $user->email,
        'source' => 'mobile',
    ]);
    $response->assertStatus(200);
    expect($response->json()['message'])
        ->toBe("Email Sent");
});

it('can reset password', function () {
    $profile = Visitor::factory()->create();
    $user = User::factory(['email_verified_at' => now(), 'profile_id' => $profile->id, 'profile_type' => $profile::class, 'email' => $profile->email])->create();
    $verify = Verify::factory([
        'user_id' =>  $user->id,
        'email' =>  $user->email
    ])->create();
    $response = $this->postJson('/api/v1/verify', [
        'email' => $verify->email,
        'token' => $verify->token,
        'password' => 'Password690@',
        'password_confirmation' => 'Password690@',
    ]);
    
    $response->assertStatus(200);
    expect($response->json()['message'])
        ->toBe("Password reset successful.");
});
