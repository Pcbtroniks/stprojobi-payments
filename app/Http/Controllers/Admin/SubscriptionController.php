<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentPlatform;
use Illuminate\Http\Request;
use App\Models\ProjobiUser;
use App\Services\PlatformService;
use App\Services\UserService;

class SubscriptionController extends Controller
{
    public function index()
    {
        $paymentPlatforms = PaymentPlatform::all();
        return view('admin.dashboard.subscription', compact('paymentPlatforms'));
    }

    public function projobi()
    {
        return response()->json(ProjobiUser::all());
    }

    public function activate(PlatformService $platform ,ProjobiUser $user, $activate)
    {
        return $platform->activateSubscription($user, $activate);
    }

    public function projobiUser()
    {
        if(session()->has('projobi_user'))
        {
            return response()->json(session('projobi_user'));
        } 
        else
        {
            return response()->json(['error' => 'No user session found']);
        }
    }

    public function setProjobiUser(UserService $userService, $userID)
    {
        return $userService->setUserSession($userID);
    }

    public function deleteProjobiSession(UserService $userService)
    {
        return $userService->deleteUserSession();
    }
}
