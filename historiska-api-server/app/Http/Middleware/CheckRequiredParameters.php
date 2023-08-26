<?php

namespace App\Http\Middleware;

use App\Custom\SendResponse;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRequiredParameters
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next, ...$args): Response
    {
        $param = $request->json()->all();

        foreach ($args as $arg) {
            if (!array_key_exists($arg, $param)) {
                return SendResponse::bad_request("Missing required field : $arg");
            }
        }
        return $next($request);
    }
}
