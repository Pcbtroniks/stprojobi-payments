<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HandShakeController extends Controller
{

    public function handShake()
    {
        return redirect()->route('subscribe.show');
    }
}
