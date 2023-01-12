<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class VerifyProjobiUser
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

        if(session()->has('projobi_user'))
        {
            return $next($request);
        }

        $projobi_url = config('app.env') === 'local' ? 'http://projobi.test/dashboard' : 'https://projobi.com/dashboard';

        return redirect($projobi_url);

    }
}
