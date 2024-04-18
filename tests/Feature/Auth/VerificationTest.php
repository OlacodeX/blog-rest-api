<?php

use App\Models\Admin;
use App\Models\User;
use App\Models\Verify;
use App\Models\Visitor;

it('can verify account', function () {
    $profile = Visitor::factory()->create();
    $user = User::factory(['email_verified_at' => now(), 'profile_id' => $profile->id, 'profile_type' => $profile::class, 'email' => $profile->email])->create();
    $verify = Verify::factory([
        'user_id' =>  $user->id,
        'email' =>  $user->email
    ])->create();
    $response = $this->postJson('/api/v1/verify', [
        'token' => $verify->token
    ]);

    $response->assertStatus(200);
    expect($response->json()['message'])
        ->toBe("Email Address Verified.");
});


it('can resend verification email', function () {
    $profile = Admin::factory()->create();
    $user = User::factory(['profile_id' => $profile->id, 'profile_type' => $profile::class, 'email' => $profile->email])->create();
    $response = $this->postJson('/api/v1/resendVerificationMail', [
        'email' => $user->email,
    ]);

    $response->assertStatus(200);
    expect($response->json()['message'])
        ->toBe("Verification Email Resent.");
});