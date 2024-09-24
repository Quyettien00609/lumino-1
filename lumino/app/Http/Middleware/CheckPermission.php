<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;

class CheckPermission
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (Response|\Illuminate\Http\RedirectResponse)  $next
     * @param  string|null  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request, Closure $next, $permission = null)
    {
        $user = Auth::user();

        if ($user && ($user->hasRole('super-admin') || ($permission && $user->can($permission)))) {
            return $next($request);
        }

        return response()->json(['error' => 'Bạn không có quyền truy cập vào tài nguyên này.'], 403);
    }
}
