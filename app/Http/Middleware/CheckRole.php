<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ...$roleid)
    {
        if (!Auth::check()) {
            return response()->json(['message' => 'User not authenticated'], 401);
        }
        $userRole = Auth::user()->roleid;
        $roleid = Role::whereIn('name', $roleid)->pluck('id')->toArray();
        if (!in_array($userRole, $roleid)) {
            return response()->json(['message' => 'Unauthorized: You do not have the required role'], 403);
        }

        return $next($request);
    }
}
