<?php

namespace App\Traits;

use App\Mail\PasswordReset;
use App\Mail\UserVerification;
use App\Models\Verify;
use Exception;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

trait ShouldVerify
{
    private function generateVerifier(): Verify
    {
        $verifyUser = Verify::create([
            'user_id' => $this->id,
            'token' => Str::random(6),
            'email' => $this->email,
            'expires_at' => now()->addMinutes(180)
        ]);

        return $verifyUser;
    }

    public function sendVerificationEmail()
    {
        $verifyUser = $this->generateVerifier();
        try {
            Mail::to($verifyUser->email)->send(new UserVerification($verifyUser->token));
        } catch (Exception $e) {
            return $e->getMessage();
        }
        
        return $verifyUser;
    }

    
    public function sendPasswordResetEmail(string $source)
    {
        $verifyUser = $this->generateVerifier();
        $url = $source == 'web' ? config('custom.frontend_url').'/reset_password'.'/'.$verifyUser->token : null;
        $code = $source == 'web' ? null : $verifyUser->token;
        try {
            Mail::to($verifyUser->email)->send(new PasswordReset($url, $code));
        } catch (Exception $e) {
            return $e->getMessage();
        }
        return $verifyUser;
    }

    public function verify(Verify $verifier, ?String $password)
    {
        $user = $verifier->user;
        $password ? $user->password = Hash::make($password) : null;
        !$user->email_verified_at ? $user->email_verified_at = now() : null;
        $user->save();
        $verifier->delete();
        return ;
    }
}
