<?php

namespace App\Traits;
use Carbon\Carbon;


trait UseSubscription {

    public function useActivateSubscription($Plan, $ProjobiUser)
    {
        $ProjobiUser->subscription_status = 'active';
        $ProjobiUser->subscription_active_until = Carbon::parse($ProjobiUser->subscription_active_until)->addDays($Plan->duration_in_days);
        $ProjobiUser->post_limit = $this->getPostLimitByDurationDays($Plan->duration_in_days);

        return $ProjobiUser->save();
    }

    public function getPostLimitByDurationDays($duration_in_days)
    {
        $postsLimit = 0;
        $planDuration = $duration_in_days;
        switch ($planDuration) {
            case 30:
                $postsLimit = 1;
                break;
            case 180:
                $postsLimit = 2;
                break;
            case 365:
                $postsLimit = 3;
                break;
            
            default:
                $postsLimit = 1;
                break;
        }
        return $postsLimit;
    }

}