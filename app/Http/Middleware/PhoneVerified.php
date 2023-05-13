<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PhoneVerified
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!$request->user()->phone_verified_at && $request->user()->role !== 'admin')
        {
            // if(!session('after_login'))
            // {
                session(['after_login' => $request->url()]);
            // }
            return redirect(
                route("verify.phone")
            );
        }
        return $next($request);
    }
}
