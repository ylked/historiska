<?php

namespace App\Http\Middleware;

use App\Custom\SendResponse;
use App\Models\user;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckTokenValidity
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $token = $request->header('Authorization');

        if (is_null($token)) {
            return SendResponse::unauthorized('Missing required auth token');
        }

        // find the user from the given token
        $user = user::where('token', $token);

        // if no user has been found
        if ($user->count() != 1) {
            return SendResponse::unauthorized('Invalid auth token');
        }
        $user = $user->first();

        // if no timestamp
        if (is_null($user->token_issued_at)) {
            return SendResponse::unauthorized('Invalid auth token');
        }

        // get timestamp of last issued token, as Carbon object
        $ts = Carbon::parse($user->token_issued_at);

        // if token has expired
        if ($ts->diffInMinutes(Carbon::now()) > config('historiska.token_lifetime.auth')) {
            return SendResponse::unauthorized('Invalid auth token');
        }

        // add user id attribute in the request
        $request->attributes->add(['user' => $user->id]);

        return $next($request);
    }
}
