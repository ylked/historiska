<?php

namespace App\Http\Controllers;

use App\Custom\SendResponse;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Http\Request;

class RewardController extends Controller
{

    protected function get_reward_time($base)
    {
        return $base
            ->addHours(config('historiska.reward.time.h'))
            ->addMinutes(config('historiska.reward.time.m'));
    }

    protected function get_reward_time_today()
    {
        return $this->get_reward_time(Carbon::today());
    }

    protected function get_reward_time_yesterday()
    {
        return $this->get_reward_time(Carbon::yesterday());
    }

    protected function get_reward_time_tomorrow()
    {
        return $this->get_reward_time(Carbon::tomorrow());
    }

    protected function get_next_opening_time()
    {
        $t = $this->get_reward_time_today();
        $d = $this->get_reward_time_tomorrow();
        $n = Carbon::now();

        if ($n->lessThan($t)) {
            $next = $t;
        } else {
            $next = $d;
        }
        return $next;
    }

    protected function can_reward_be_open(user $user)
    {
        $t = $this->get_reward_time_today();
        $y = $this->get_reward_time_yesterday();
        $n = Carbon::now();
        $l = Carbon::parse($user->last_pack_openend_at);

        return $y->greaterThan($l)
            or ($n->greaterThan($t) and $l->lessThan($t))
            or is_null($user->last_pack_openend_at);
    }

    public function status(Request $request)
    {
        $user = user::find($request->get('user'));
        $available = $this->can_reward_be_open($user);
        $next = $this->get_next_opening_time();

        return SendResponse::success('success', [
            'is_available' => $available,
            'next_opening' => $next
        ]);
    }
}
