<?php

namespace App\Services;

use App\Models\ProjobiUser;
use Illuminate\Support\Facades\Storage;

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

    public static function getExpiredSubscriptions()
    {
        return ProjobiUser::where('is_subscriber', 'yes')->whereDate('subscription_active_until', '<', now())->get();
    }

    public static function removeExpiredSubscriptions()
    {
        $expiredSubscriptions = self::getExpiredSubscriptions();
        foreach($expiredSubscriptions as $expiredSubscription)
        {
            $expiredSubscription->is_subscriber = 'no';
            $expiredSubscription->subscription_status = 'cancelled';
            $expiredSubscription->save();
        }
        self::log('Expired Subscriptions: ' . $expiredSubscriptions->count() . ' subscriptions removed');
        return response(['message' => 'Expired Subscriptions: ' . $expiredSubscriptions->count() . ' subscriptions removed'], 200);
    }

    public static function log($message = 'No message')
    {
        $log =  '=================================================================================================================='. PHP_EOL .
                date(DATE_RFC2822) . " $message " . PHP_EOL .
                '=================================================================================================================='. PHP_EOL;
        Storage::append('userService.log', $log);
    }

}