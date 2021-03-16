<?php

namespace App\Http\Middleware;

use Closure;

class PreflightResponse
{
    public function handle($request, Closure $next )
    {
        if ($request->getMethod() == "OPTIONS") {

			return response()->json([
				'message' => 'Unauthenticated.',
			], 401);

        }

		return $next($request);
     }
 }
