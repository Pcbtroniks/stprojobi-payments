<?php

namespace App\Services;

use App\Models\ProjobiUser;

class UserService
{
    public function setUserSession($userID)
    {
        $user = $this->validateUser($userID);
        if($user)
        {
            session([
                'projobi_user'=> [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'is_subscriber' => $user->is_subscriber
                ],
            ]);
        }
    }

    public static function setSession($userID)
    {
        self::setUserSession($userID);
    }

    public function getUserSession()
    {
        return session('projobi_user');
    }

    public function validateUser($userID)
    {
        return ProjobiUser::find($userID);
    }

    public static function deleteUserSession()
    {
        session()->forget('projobi_user');
    }

}