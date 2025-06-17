<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

final class CheckSimpleAuth
{
    
    public function handle(Request $request, Closure $next): mixed
    {
        dd("ss");
        if(empty($request->header('Authentication'))) {
            return response()->json(['message' => 'Missing Authentication header'], 401);
        }

        $authHeader = $request->header('Authentication');

        if ($authHeader !== env("API_AUTENTICACION")) {
            return response()->json(['message' => 'Unauthorized'], 401);
        }

        return $next($request);
    }
}
