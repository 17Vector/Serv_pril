<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, ...$permissions)
    {
        $user = Auth::user();

        if (!$user) {
            if (in_array('user-get-list', $permissions))
            {
                return $next($request);
            }
            else
            {
                return response()->json(['error' => 'Unauthorized'], 401);
            }
        }

        foreach ($permissions as $permission)
        {
            if ($user->hasPermission($permission))
            {
                return $next($request);
            }
        }

        $requiredPermissions = implode(', ', $permissions);
        return response()->json(['error' => "Для выполнения данной операции необходимо разрешение: $requiredPermissions"], 403);
    }
}
