<?php
namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 *
 */
trait MakeUser
{
    public function user()
    {
        return $this->morphOne(User::class, 'profile');
    }

    public function makeUser (string $password = '')
    {
        $user = $this->user ?? new User();
        $user['name'] = $this->name;
        $user['email'] = $this->email;
        !$this->user ? $user['password'] = $password : null;
        !$this->user ? $user['profile_id'] = $this->id : null;
        !$this->user ? $user['profile_type'] = $this::class : null;

        $user->save();

        if(method_exists($user, 'sendVerificationEmail') && empty($user->email_verified_at) && !$this->user)
        {
            $user->sendVerificationEmail();
        }

        return $user;
    }

}
