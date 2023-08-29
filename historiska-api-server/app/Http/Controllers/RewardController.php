<?php

namespace App\Http\Controllers;

use App\Custom\SendResponse;
use App\Models\card;
use App\Models\card_entity;
use App\Models\category;
use App\Models\continent;
use App\Models\country;
use App\Models\user;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Collection;
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

    protected function get_random_card(Collection $cards): ?card
    {
        $sum = $cards->sum('rarity');
        $rand = random_int(0, $sum);

        foreach ($cards as $card) {
            if ($rand < $card->rarity) {
                return $card;
            }
            $rand -= $card->rarity;
        }
        return $cards->first();
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

    public function open(Request $request)
    {
        $user = user::find($request->get('user'));
        if (!$this->can_reward_be_open($user)) {
            return SendResponse::forbidden('Reward is not available yet');
        }

        $result = [];
        $all_cards = card::all();

        for ($i = 0; $i < config('historiska.reward.count'); $i++) {
            $card = $this->get_random_card($all_cards);

            if (is_null($card)) {
                return SendResponse::bad_request('unknown error');
            }

            $new = (card_entity
                    ::where('card', $card->id)
                    ->where('owner', $user->id)
                    ->count() == 0);

            $gold = ((random_int(0, 1000) / 10.0) < config('historiska.reward.gold_rarity_percent'));

            $entity = new card_entity();
            $entity->card = $card->id;
            $entity->owner = $user->id;
            $entity->is_gold = $gold;
            $entity->save();

            $result[] = [
                'id' => $card->id,
                'name' => $card->name,
                'description' => $card->description,
                'code' => $card->code,
                'birth' => $card->birth,
                'death' => $card->death,
                'img' => $card->img_path,
                'is_new' => $new,
                'is_gold' => $gold,
                'category' => [
                    'id' => $card->category,
                    'name' => category::find($card->category)->name,
                ],
                'country' => [
                    'id' => $card->country,
                    'name' => country::find($card->country)->name,
                ],
                'continent' => [
                    'id' => country::find($card->country)->continent,
                    'name' => continent::find(country::find($card->country)->continent)->name,
                ],
            ];
        }

        $user->last_pack_openend_at = Carbon::now();
        $user->save();

        return SendResponse::success('success', $result);
    }
}
