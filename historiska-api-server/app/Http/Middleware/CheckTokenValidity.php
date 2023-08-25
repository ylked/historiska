<?php

namespace App\Http\Middleware;

use App\Models\user;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenValidity
{
    protected function unauthorized($message)
    {
        return response()->json(
            [
                'success' => false,
                'status' => 401,
                'error' => 'UNAUTHORIZED',
                'message' => $message
            ], 401
        );
    }

    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (is_null($token)) {
            return $this->unauthorized('Missing required auth token');
        }

        // find the user from the given token
        $user = user::where('token', $token);

        // if no user has been found
        if ($user->count() != 1) {
            return $this->unauthorized('Invalid auth token');
        }
        $user = $user->first();

        // if no timestamp
        if (is_null($user->token_issued_at)) {
            return $this->unauthorized('Invalid auth token');
        }

        // get timestamp of last issued token, as Carbon object
        $ts = \Carbon\Carbon::parse($user->token_issued_at);

        // if token has expired
        if ($ts->diffInMinutes(\Carbon\Carbon::now()) > env('HISTORISKA_AUTH_TOKEN_LIFETIME')) {
            return $this->unauthorized('Invalid auth token');
        }

        // add user id attribute in the request
        $request->attributes->add(['user' => $user->id]);

        return $next($request);
    }
}