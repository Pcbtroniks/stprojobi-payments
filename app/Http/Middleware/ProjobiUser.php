<?php

namespace App\Http\Middleware;

use App\Services\UserService;
use Closure;
use Illuminate\Http\Request;

class ProjobiUser
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($this->validateHandShake($request) || auth()->check())
        {
            (new UserService)->setUserSession($request->user_id);

            return $next($request);
        }
        return redirect()->back()->with(['message' => 'Invalid HandShake', 'data' => $request->all(), 'secret' => config('services.projobi.secret')]);
    }

    public function validateHandShake(Request $request)
    {
        if(isset($request->handShake) && $request->handShake === config('services.projobi.secret'))
        {
            return true;
        }

        return false;
    }
}
