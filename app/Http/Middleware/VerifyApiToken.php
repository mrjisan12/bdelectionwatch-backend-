<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class VerifyApiToken
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('X-API-TOKEN');

        if (!$token || $token !== config('app.report_api_token')) {
            return response()->json([
                'message' => 'Unauthorized request'
            ], 401);
        }

        return $next($request);
    }
}
