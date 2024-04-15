<?php
namespace App\Traits;

use App\Models\User;
use Illuminate\Database\Eloquent\Casts\Attribute;

/**
 *
 */
trait MakeUser
{

    public function makeUser (string $password)
    {
        $user = new User();
        $user['name'] = $this->name;
        $user['email'] = $this->email;
        $user['password'] = $password;
        $user['profile_id'] = $this->id;
        $user['profile_type'] = $this::class;

        $user->save();

        if(method_exists($user, 'sendVerificationEmail') && empty($user->email_verified_at))
        {
            return $user->sendVerificationEmail();
        }

        return $user;
    }

}
