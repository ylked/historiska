<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
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

    protected function not_found($message)
    {
        return response()->json(
            [
                'success' => false,
                'status' => 404,
                'error' => 'NOT FOUND',
                'message' => $message
            ], 404
        );
    }

    protected function bad_request($message)
    {
        return response()->json(
            [
                'success' => false,
                'status' => 400,
                'error' => 'BAD REQUEST',
                'message' => $message
            ], 400
        );
    }

    protected function forbidden($message)
    {
        return response()->json(
            [
                'success' => false,
                'status' => 403,
                'error' => 'FORBIDDEN',
                'message' => $message
            ], 403
        );

    }

    protected function success($message, $content)
    {
        return response()->json(
            [
                'success' => true,
                'status' => 200,
                'error' => null,
                'message' => $message,
                'content' => $content
            ], 200
        );
    }

    protected function is_email_valid($email)
    {
        $validator = Validator::make(['email' => $email], ['email' => 'email:rfc,dns']);
        try {
            $validator->validate();
            return true;
        } catch (ValidationException $e) {
            return false;
        }
    }

    protected function is_username_valid($username)
    {
        // source : https://stackoverflow.com/questions/12018245/regular-expression-to-validate-username
        $validator = Validator::make(['username' => $username],
            [
                'username' => 'regex:"^(?=.{3,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$"|max:255|unique:user,username'
            ]);

        try {
            $validator->validate();
            return true;
        } catch (ValidationException $e) {
            return false;
        }
    }

    public function login(Request $request)
    {
        $param = $request->json()->all();

        if (!array_key_exists('id', $param)) {
            return $this->bad_request("required attribute 'id' not in request body. Please specify username or e-mail address");
        }

        if (!array_key_exists('password', $param)) {
            return $this->bad_request("required attribute 'password' not in request body");
        }

        $user = user::where('username', $param['id'])->orWhere('email', $param['id']);

        if ($user->count() != 1) {
            return $this->unauthorized('Incorrect credentials');
        }

        $user = $user->first();

        if (!Hash::check($param['password'], $user->password)) {
            return $this->unauthorized('Incorrect credentials');
        }

        // create a unique random token
        do {
            $token = Str::random(256);
        } while (user::where('token', $token)->get()->count() != 0);

        $user->token = $token;
        $user->token_issued_at = \Carbon\Carbon::now();
        $user->save();

        return $this->success(
            "user " . $user->username . " logged in",
            [
                'token' => $token,
                'expires_at' => \Carbon\Carbon::now()->addMinutes(env('HISTORISKA_AUTH_TOKEN_LIFETIME')),
                'verified' => boolval($user->is_activated)
            ]
        );
    }

    public function register(Request $request)
    {
        $param = $request->json()->all();

        if (!array_key_exists('username', $param)) {
            return $this->bad_request('Required field \'username\' not in request body');
        }

        if (!array_key_exists('email', $param)) {
            return $this->bad_request('Required field \'email\' not in request body');
        }

        if (!array_key_exists('password', $param)) {
            return $this->bad_request('Required field \'password\' not in request body');
        }

        if (user::where('username', $param['username'])->get()->isNotEmpty()) {
            return $this->forbidden('Username already taken');
        }

        if (!$this->is_email_valid($param['email'])) {
            return $this->forbidden('E-mail format is not valid');
        }

        if (user::where('email', $param['email'])->get()->isNotEmpty()) {
            return $this->forbidden('E-mail already used');
        }

        $user = new user;
        $user->username = $param['username'];
        $user->email = $param['email'];
        $user->password = Hash::make($param['password']);

        // create a unique random token
        do {
            $token = Str::random(256);
        } while (user::where('token', $token)->get()->count() != 0);

        // create a unique random activation code
        do {
            $code = Str::random(8);
        } while (user::where('activation_code', $code)->get()->count() != 0);

        $now = \Carbon\Carbon::now();

        $user->token = $token;
        $user->token_issued_at = $now;

        $user->activation_code = $code;
        $user->activation_code_sent_at = $now;

        $user->save();

        // TODO send e-mail

        return $this->success(
            "user " . $user->username . " successfully created",
            [
                'token' => $token,
                'expires_at' => \Carbon\Carbon::now()->addMinutes(env('HISTORISKA_AUTH_TOKEN_LIFETIME')),
                'verified' => boolval($user->is_activated)
            ]
        );
    }

    public function logout(Request $request)
    {
        $param = $request->json()->all();

        $id = $request->get('user');

        $user = user::where('id', $id)->get()->first();

        $user->token = null;
        $user->token_issued_at = null;
        $user->save();

        return $this->success('successfully logged out', []);
    }

    public function username_availability(Request $request)
    {
        $username = $request->route('username');

        $available = user::where('username', $username)->get()->count() == 0;
        $valid = $this->is_username_valid($username);

        return $this->success('username validity and availability checked',
            [
                'is_available' => $available,
                'is_valid' => $valid
            ]);
    }

    public function email_availability(Request $request)
    {
        $email = $request->route('email');

        $valid = $this->is_email_valid($email);
        $available = user::where('email', $email)->get()->count() == 0;

        return $this->success('email validity and availability checked',
            [
                'is_available' => $available,
                'is_valid' => $valid
            ]);
    }

    public function activate(Request $request)
    {
        $user = user::where('activation_code', $request->route('code'))->get();
        $now = Carbon::now();

        if ($user->count() != 1) {
            return $this->not_found('Invalid activation code');
        }

        $user = $user->first();

        // should not happen since activation code is deleted after activation
        if (boolval($user->is_activated)) {
            // fix database entry
            $user->activation_code = null;
            $user->activation_code_sent_at = null;
            $user->save();

            return $this->forbidden('Account already verified');
        }

        $ts = Carbon::parse($user->activation_code_sent_at);

        // check if code is expired
        if ($ts->diffInMinutes($now) > env('HISTORISKA_ACTIVATION_CODE_LIFETIME')) {
            // delete code since it expired
            $user->activation_code = null;
            $user->activation_code_sent_at = null;
            $user->save();

            return $this->not_found('Invalid activation code');
        }

        $user->activation_code = null;
        $user->activation_code_sent_at = null;
        $user->is_activated = true;
        $user->save();

        return $this->success('Account successfully activated');
    }
}
