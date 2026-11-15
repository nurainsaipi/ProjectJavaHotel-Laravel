<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AuthAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (!session('admin_auth')) {
            return redirect()->route('login');
        }
        return $next($request);
    }
}
