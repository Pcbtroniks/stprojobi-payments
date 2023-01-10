<?php

namespace App\Services;

use App\Models\ProjobiUser;

class PlatformService
{
    public function activateSubscription(ProjobiUser $user, $activate = 'yes')
    {
        $activateEnums = ['yes', 'no'];
        if (in_array($activate, $activateEnums))
        {
            $user->is_subscriber = $activate;
            return $user->save();
        } 
        else 
        {
            return false;
        }
    }
}