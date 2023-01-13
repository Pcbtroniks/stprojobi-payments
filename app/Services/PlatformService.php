<?php

namespace App\Services;

use App\Models\ProjobiUser;

class PlatformService
{
    public function activateSubscription(ProjobiUser $user, $activate = 'yes', $subscriptionID, $plan_slug, $subcriptionActiveUntil)
    {
        $activateEnums = ['yes', 'no'];
        if (in_array($activate, $activateEnums))
        {
            $user->is_subscriber = $activate;
            $user->subscription_id = $subscriptionID;
            $user->subscription_active_until = $subcriptionActiveUntil;
            $user->plan_slug = $plan_slug;

            if(session()->has('projobi_user'))
            {
                session()->put('projobi_user.is_subscriber', $activate);
            }

            return $user->save();
        } 
        else 
        {
            return false;
        }
    }

    public function deactivateSubscription($subscriptionID)
    {
        $user = ProjobiUser::where('subscription_id', $subscriptionID)->first();
        if($user)
        {
            $user->is_subscriber = 'no';
            $user->subscription_active_until = now();

            if(session()->has('projobi_user'))
            {
                session()->put('projobi_user.is_subscriber', 'no');
            }

            return $user->save();
        }
        else
        {
            return false;
        }
    }

    public function reactivateSubscription($subscriptionID)
    {
        $user = ProjobiUser::where('subscription_id', $subscriptionID)->first();
        if($user)
        {
            $user->is_subscriber = 'yes';

            if(session()->has('projobi_user'))
            {
                session()->put('projobi_user.is_subscriber', 'yes');
            }

            return $user->save();
        }
        else
        {
            return false;
        }
    }
}