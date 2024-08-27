<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (! auth()->user()->hasRole('admin')) {
            return response()->json(['data' => ['message' => 'Opps! You do not have permission to access.']], Response::HTTP_UNAUTHORIZED);
        }

        return $next($request);
    }
}
