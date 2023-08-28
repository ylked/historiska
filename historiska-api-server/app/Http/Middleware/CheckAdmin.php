<?php

namespace App\Http\Middleware;

use App\Custom\SendResponse;
use App\Models\user;
use Closure;
use Illuminate\Http\Request;

class CheckAdmin
{
    public function handle(Request $request, Closure $next)
    {
        $id = $request->get('user');
        if (is_null($id)) {
            return SendResponse::bad_request('Could not determine current user');
        }

        $user = user::where('id', $id)->get()->first();
        if (!$user->is_admin) {
            return SendResponse::forbidden('You do not have the rights to access this resource');
        }
        return $next($request);
    }
}
