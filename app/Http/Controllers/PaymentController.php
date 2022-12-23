<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function pay(Request $request)
    {
        $rules = [
            'plan' => 'required|string',
            'currency' => 'required|exists:currencies,iso_code',
            'payment_platform' => 'required|exists:payment_platforms,id'
        ];

        $request->validate($rules);

        return $request->all();
    }

    public function approval()
    {
        return 'Aproved';
    }
    
    public function cancelled()
    {
        return 'Cancelled';
    }
}
