<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentPlatform;
use Illuminate\Http\Request;
use App\Models\ProjobiUser;

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
}
