<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLoginMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Don't auto-login on guest-facing landing page and auth pages
        // to allow genuine guest visitors to see login/register prompts
        if (! Auth::check() && ! $request->is('/', 'login', 'register')) {
            try {
                $defaultUser = User::where('email', 'zidan@olara.id')->first() ?? User::first();
                if ($defaultUser) {
                    Auth::login($defaultUser);
                }
            } catch (\Throwable $e) {
                // Ignore DB error during auto login
            }
        }

        return $next($request);
    }
}
