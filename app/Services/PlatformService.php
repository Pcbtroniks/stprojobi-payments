<?php

namespace App\Services;

use App\Models\Plan;
use App\Models\ProjobiUser;
use Carbon\Carbon;
use App\Traits\UseSubscription;

class PlatformService
{
    use UseSubscription;
    public function activateSubscription(ProjobiUser $user, $activate = 'yes', $subscriptionID, $plan_slug, $subcriptionActiveUntil)
    {
        $activateEnums = ['yes', 'no'];
        if (in_array($activate, $activateEnums))
        {
            $user->is_subscriber = $activate;
            $user->subscription_id = $subscriptionID;
            $user->subscription_status = 'active';
            $user->subscription_active_until = $subcriptionActiveUntil;
            $user->plan_slug = $plan_slug;
            $user->post_limit = $this->getPostLimitByDurationDays($this->getPlanBySlug($plan_slug)->duration_in_days);

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

    public function suspendSubscription($subscriptionID)
    {
        $user = ProjobiUser::where('subscription_id', $subscriptionID)->first();
        if($user)
        {
            $user->subscription_status = 'suspended';

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
            $user->subscription_status = 'active';
            $user->post_limit = $this->getPostLimitByDurationDays($this->getPlanBySlug($user->plan_slug)->duration_in_days);

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

    public function paymentCompleted($subscriptionID, $lastDate = null)
    {
        $user = ProjobiUser::where('subscription_id', $subscriptionID)->first();
        $plan = Plan::where('slug', $user->plan_slug)->first();
        if($user)
        {
            return $this->useActivateSubscription($plan, $user);
        }
        else
        {
            return false;
        }
    }
}