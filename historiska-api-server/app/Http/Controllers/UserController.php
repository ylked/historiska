<?php

namespace App\Http\Controllers;

use App\Custom\SendResponse;
use App\Mail\AccountDeletionMail;
use App\Mail\ActivateMail;
use App\Mail\RecoveryMail;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{

    /**
     * @param $email string The e-mail to check
     * @return bool True if e-mail is valid, false if not
     */
    protected function is_email_valid(string $email): bool
    {
        $validator = Validator::make(['email' => $email], ['email' => 'email:rfc,dns']);
        try {
            $validator->validate();
            return true;
        } catch (ValidationException) {
            return false;
        }
    }

    /**
     * @param $username string The username to check
     * @return bool True if the username is valid, false if not
     */
    protected function is_username_valid(string $username): bool
    {
        // source for regex : https://stackoverflow.com/questions/12018245/regular-expression-to-validate-username
        $validator = Validator::make(['username' => $username],
            [
                'username' => 'regex:"^(?=.{3,20}$)(?![_.])(?!.*[_.]{2})[a-zA-Z0-9._]+(?<![_.])$"|max:255|unique:user,username'
            ]);

        try {
            $validator->validate();
            return true;
        } catch (ValidationException) {
            return false;
        }
    }

    /**
     * @param $password string The password to check
     * @return bool True if the password meet requirements, false if not
     */
    protected function is_password_valid(string $password): bool
    {
        // source for regex : https://stackoverflow.com/questions/19605150/regex-for-password-must-contain-at-least-eight-characters-at-least-one-number-a
        $validator = Validator::make(['password' => $password],
            [
                'password' => 'regex:"^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$"'
            ]);

        try {
            $validator->validate();
            return true;
        } catch (ValidationException) {
            return false;
        }
    }

    protected function send_activation_email(string $to, string $code)
    {
        Mail::to($to)->send(new ActivateMail($code));
    }

    protected function send_recovery_email(string $to, string $code)
    {
        Mail::to($to)->send(new RecoveryMail($code));
    }

    protected function send_deletion_email(string $to, string $code)
    {
        Mail::to($to)->send(new AccountDeletionMail($code));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function login(Request $request): JsonResponse
    {
        $param = $request->json()->all();

        $user = user::where('username', $param['id'])->orWhere('email', $param['id']);

        if ($user->count() != 1) {
            return SendResponse::unauthorized('Incorrect credentials');
        }

        $user = $user->first();

        if (!Hash::check($param['password'], $user->password)) {
            return SendResponse::unauthorized('Incorrect credentials');
        }

        // create a unique random token
        do {
            $token = Str::random(config('historiska.token_length.auth'));
        } while (user::where('token', $token)->get()->count() != 0);

        $user->token = $token;
        $user->token_issued_at = Carbon::now();
        $user->save();

        return SendResponse::success(
            "user " . $user->username . " logged in",
            [
                'token' => $token,
                'expires_at' => Carbon::now()->addMinutes(config('historiska.token_lifetime.auth')),
                'verified' => boolval($user->is_activated)
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function register(Request $request): JsonResponse
    {
        $param = $request->json()->all();


        if (user::where('username', $param['username'])->get()->isNotEmpty()) {
            return SendResponse::forbidden('Username already taken');
        }

        if (!$this->is_username_valid($param['username'])) {
            return SendResponse::forbidden('Username is in incorrect format');
        }

        if (!$this->is_email_valid($param['email'])) {
            return SendResponse::forbidden('E-mail format is not valid');
        }

        if (user::where('email', $param['email'])->get()->isNotEmpty()) {
            return SendResponse::forbidden('E-mail already used');
        }

        if (!$this->is_password_valid($param['password'])) {
            return SendResponse::forbidden('Password does not meet requirements');
        }

        $user = new user;
        $user->username = $param['username'];
        $user->email = $param['email'];
        $user->password = Hash::make($param['password']);

        // create a unique random token
        do {
            $token = Str::random(config('historiska.token_length.auth'));
        } while (user::where('token', $token)->get()->count() != 0);

        // create a unique random activation code
        do {
            $code = Str::random(config('historiska.token_length.activation'));
        } while (user::where('activation_code', $code)->get()->count() != 0);

        $now = Carbon::now();

        $user->token = $token;
        $user->token_issued_at = $now;

        $user->activation_code = $code;
        $user->activation_code_sent_at = $now;

        $user->save();

        $this->send_activation_email($user->email, $user->activation_code);

        return SendResponse::success(
            "user " . $user->username . " successfully created",
            [
                'token' => $token,
                'expires_at' => Carbon::now()->addMinutes(config('historiska.token_lifetime.auth')),
                'verified' => boolval($user->is_activated)
            ]
        );
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function logout(Request $request): JsonResponse
    {
        $id = $request->get('user');

        $user = user::where('id', $id)->get()->first();

        $user->token = null;
        $user->token_issued_at = null;
        $user->save();

        return SendResponse::success('successfully logged out');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function username_availability(Request $request): JsonResponse
    {
        $username = $request->route('username');

        $available = user::where('username', $username)->get()->count() == 0;
        $valid = $this->is_username_valid($username);

        return SendResponse::success('username validity and availability checked',
            [
                'is_available' => $available,
                'is_valid' => $valid
            ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function email_availability(Request $request): JsonResponse
    {
        $email = $request->route('email');

        $valid = $this->is_email_valid($email);
        $available = user::where('email', $email)->get()->count() == 0;

        return SendResponse::success('email validity and availability checked',
            [
                'is_available' => $available,
                'is_valid' => $valid
            ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function activate(Request $request): JsonResponse
    {
        $user = user::where('activation_code', $request->route('code'))->get();
        $now = Carbon::now();

        if ($user->count() != 1) {
            return SendResponse::not_found('Invalid activation code');
        }

        $user = $user->first();

        // should not happen since activation code is deleted after activation
        if ($user->is_activated) {
            // fix database entry
            $user->activation_code = null;
            $user->activation_code_sent_at = null;
            $user->save();

            return SendResponse::forbidden('Account already verified');
        }

        $ts = Carbon::parse($user->activation_code_sent_at);

        // whether code has expired or not, it will be deleted
        $user->activation_code = null;
        $user->activation_code_sent_at = null;

        // check if code is expired
        if ($ts->diffInMinutes($now) > config('historiska.token_lifetime.activation')) {
            $user->save();

            return SendResponse::not_found('Invalid activation code');
        }

        $user->is_activated = true;
        $user->save();

        return SendResponse::success('Account successfully activated');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function resend(Request $request): JsonResponse
    {
        $id = $request->get('user');
        $user = user::where('id', $id)->get()->first();
        $now = Carbon::now();

        if ($user->is_activated) {
            return SendResponse::forbidden('Account already activated');
        }

        $ts = Carbon::parse($user->activation_code_sent_at);
        $cooldown = config('historiska.email_cooldown');
        $diff = $ts->diffInMinutes($now);

        if (!is_null($user->activation_code_sent_at) and $diff < $cooldown) {
            return SendResponse::too_many_requests("Last e-mail was sent less than $cooldown minutes ago ($diff min ago)");
        }

        // create a unique random activation code
        do {
            $code = Str::random(config('historiska.token_length.activation'));
        } while (user::where('activation_code', $code)->get()->count() != 0);

        $user->activation_code = $code;
        $user->activation_code_sent_at = $now;
        $user->save();

        $this->send_activation_email($user->email, $user->activation_code);

        return SendResponse::success('An e-mail with a new activation code has been sent');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function forgot(Request $request): JsonResponse
    {
        $param = $request->json()->all();
        $now = Carbon::now();
        $user = user::where('username', $param['id'])->orWhere('email', $param['id']);

        if ($user->get()->count() != 1) {
            return SendResponse::success('If the username or e-mail address exists, an e-mail with instruction to recover your account has been sent to you.');
        }

        $user = $user->first();
        $ts = Carbon::parse($user->recovery_code_sent_at);
        $diff = $ts->diffInMinutes($now);
        $cooldown = config('historiska.email_cooldown');

        if (!is_null($user->recovery_code_sent_at) and $diff < $cooldown) {
            return SendResponse::too_many_requests("Last e-mail was sent less than $cooldown minutes ago ($diff min ago)");
        }

        $user->recovery_code = Str::random(config('historiska.token_length.recovery'));
        $user->recovery_code_sent_at = $now;
        $user->save();

        $this->send_recovery_email($user->email, $user->recovery_code);

        return SendResponse::success('If the username or e-mail address exists, an e-mail with instruction to recover your account has been sent to you.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function recover(Request $request): JsonResponse
    {
        $param = $request->json()->all();
        $user = user::where('recovery_code', $param['token']);

        if ($user->get()->count() != 1) {
            return SendResponse::not_found('Invalid recovery code (not found)');
        }

        $user = $user->first();

        $now = Carbon::now();
        $ts = Carbon::parse($user->recovery_code_sent_at);
        $diff = $ts->diffInMinutes($now);
        $lifetime = config('historiska.token_lifetime.recovery');

        if (!is_null($user->recovery_code_sent_at) and $diff > $lifetime) {
            $user->recovery_code = null;
            $user->recovery_code_sent_at = null;
            $user->save();

            return SendResponse::not_found('Invalid recovery code (expired)');
        }

        if (Hash::check($param['password'], $user->password)) {
            return SendResponse::forbidden('New password must be different than current one');
        }

        //TODO check password validity
        $user->password = Hash::make($param['password']);
        $user->recovery_code = null;
        $user->recovery_code_sent_at = null;
        $user->token = null;
        $user->token_issued_at = null;
        $user->save();

        return SendResponse::success('Password successfully reset');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function get_account_details(Request $request): JsonResponse
    {
        $user = user::where('id', $request->get('user'))->first();

        return SendResponse::success('success', [
            'username' => $user->username,
            'email' => $user->email,
            'is_verified' => boolval($user->is_activated)
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_email(Request $request): JsonResponse
    {
        $user = user::where('id', $request->get('user'))->first();
        $param = $request->json()->all();

        if (!$this->is_email_valid($param['email'])) {
            return SendResponse::forbidden('Invalid e-mail format');
        }

        if ($param['email'] == $user->email) {
            return SendResponse::forbidden('New e-mail must be different than current one');
        }

        if (user::where('email', $param['email'])->get()->count() != 0) {
            return SendResponse::forbidden('E-mail already used');
        }

        $user->email = $param['email'];
        $user->is_activated = false;

        // create a unique random activation code
        do {
            $code = Str::random(config('historiska.token_length.activation'));
        } while (user::where('activation_code', $code)->get()->count() != 0);

        $user->activation_code = $code;
        $user->activation_code_sent_at = Carbon::now();
        $user->save();

        $this->send_activation_email($user->email, $user->activation_code);

        return SendResponse::success('E-mail successfully updated. Please confirm the e-mail by opening link sent to you.');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_username(Request $request): JsonResponse
    {
        $user = user::where('id', $request->get('user'))->get()->first();
        $username = $request->json()->get('username');

        if ($username == $user->username) {
            return SendResponse::forbidden('New username must be different than current one');
        }

        if (user::where('username', $username)->get()->count() != 0) {
            return SendResponse::forbidden('Username already taken');
        }

        if (!$this->is_username_valid($username)) {
            return SendResponse::forbidden('Username is in incorrect format');
        }

        $user->username = $username;
        $user->save();

        return SendResponse::success('Username successfully updated');
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function update_password(Request $request): JsonResponse
    {
        $user = user::where('id', $request->get('user'))->get()->first();
        $password = $request->json()->get('password');

        if (Hash::check($password, $user->password)) {
            return SendResponse::forbidden('New password must be different than current one');
        }

        if (!$this->is_password_valid($password)) {
            return SendResponse::forbidden('New password does not meet requirements');
        }

        $user->password = Hash::make($password);
        $user->save();

        return SendResponse::success('Password updated successfully');
    }

    public function initiate_deletion(Request $request)
    {
        $user = user::find($request->get('user'));
        $user->recovery_code = Str::random(config('historiska.token_length.recovery'));
        $user->recovery_code_sent_at = Carbon::now();
        $user->save();

        $this->send_deletion_email($user->email, $user->recovery_code);

        return SendResponse::success('An e-mail with a link has been sent to your e-mail address. Click the link to validate the deletion of your account');
    }

    public function validate_deletion(Request $request)
    {
        $user = user::find($request->get('user'));
        $now = Carbon::now();
        $ts = Carbon::parse($user->recovery_code_sent_at);
        $diff = $ts->diffInMinutes($now);
        $lifetime = config('historiska.token_lifetime.recovery');

        if (!is_null($user->recovery_code_sent_at) and ($diff > $lifetime)) {
            $user->recovery_code = null;
            $user->recovery_code_sent_at = null;
            $user->save();

            return SendResponse::forbidden('Deletion code expired');
        }

        if ($request->json()->get('token') != $user->recovery_code) {
            return SendResponse::not_found('Invalid validation code');
        }

        $user->delete();

        return SendResponse::success('Account deleted');
    }

    public function check_token(Request $request)
    {
        $token = $request->header('Authorization');

        if (is_null($token)) {
            return SendResponse::success('success', ['valid' => false]);
        }

        // find the user from the given token
        $user = user::where('token', $token);

        // if no user has been found
        if ($user->count() != 1) {
            return SendResponse::success('success', ['valid' => false]);
        }
        $user = $user->first();

        // if no timestamp
        if (is_null($user->token_issued_at)) {
            return SendResponse::success('success', ['valid' => false]);
        }

        // get timestamp of last issued token, as Carbon object
        $ts = Carbon::parse($user->token_issued_at);

        // if token has expired
        if ($ts->diffInMinutes(Carbon::now()) > config('historiska.token_lifetime.auth')) {
            return SendResponse::success('success', ['valid' => false]);
        }

        return SendResponse::success('success', ['valid' => true]);
    }
}
