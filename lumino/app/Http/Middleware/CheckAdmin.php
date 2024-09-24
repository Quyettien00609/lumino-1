<?php

namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        if (Auth::check() && Auth::user()->hasRole('super-admin')) {
            return $next($request);
        }

        return response()->json(['error' => 'Bạn không có quyền truy cập vào tài nguyên này.'], 403);
    }
}
