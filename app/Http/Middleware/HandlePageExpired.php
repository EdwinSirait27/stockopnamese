<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
class HandlePageExpired
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
    $response = $next($request);

    if ($response->status() === 419) {
        if (Auth::check()) {
            Auth::logout(); // logout jika user masih login
        }

        return redirect('/')->with('error', 'Session kamu telah habis. Silakan login kembali.');
    }

    return $response;
}
}