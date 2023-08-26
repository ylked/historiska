<?php

namespace App\Http\Middleware;

use App\Custom\SendResponse;
use App\Models\user;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckAccountActivation
{
    /**
     * Handle an incoming request.
     *
     * @param \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $id = $request->get('user');
        if (is_null($id)) {
            return SendResponse::bad_request('Could not determine current user');
        }

        $user = user::where('id', $id)->get()->first();
        if (!boolval($user->is_activated)) {
            return SendResponse::forbidden('Account must be activated before using this function');
        }
        return $next($request);
    }
}
