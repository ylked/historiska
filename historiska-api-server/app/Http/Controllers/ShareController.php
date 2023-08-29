<?php

namespace App\Http\Controllers;

use App\Custom\SendResponse;
use App\Models\card;
use App\Models\card_entity;
use App\Models\category;
use App\Models\continent;
use App\Models\country;
use App\Models\user;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ShareController extends Controller
{
    protected function is_more_shareable($entities, bool $gold)
    {
        return $entities
                ->where('is_shared', false)
                ->where('is_gold', $gold)
                ->count() >= 2;
    }

    public function status(Request $request)
    {
        $card = card::find($request->route('card_id'));
        $user = user::find($request->get('user'));

        if (is_null($card)) {
            return SendResponse::not_found('Invalid card ID');
        }

        $entities = card_entity
            ::where('card', $card->id)
            ->where('owner', $user->id)
            ->get();

        if ($entities->count() == 0) {
            return SendResponse::forbidden('You do not possess this card');
        }

        $result = [];
        $result['is_more_shareable'] = $this->is_more_shareable($entities, false);
        $result['is_more_shareable_gold'] = $this->is_more_shareable($entities, true);

        foreach ($entities as $entity) {
            $result['entities'][] = [
                'id' => $entity->id,
                'is_shared' => boolval($entity->is_shared),
                'code' => $entity->share_code,
            ];
        }

        return SendResponse::success('success', $result);
    }

    public function enable(Request $request)
    {
        $user = user::find($request->get('user'));
        $entity = card_entity::find($request->route('entity_id'));

        if (is_null($entity)) {
            return SendResponse::not_found('Invalid entity ID');
        }

        if ($entity->owner != $user->id) {
            return SendResponse::not_found('Invalid entity ID');
        }

        if (!$this->is_more_shareable(card_entity
            ::where('card', $entity->card)
            ->where('owner', $user->id)
            ->get(), $entity->is_gold)) {
            return SendResponse::forbidden('Card is not shareable');
        }

        if ($entity->is_shared) {
            return SendResponse::forbidden('Card is already shared');
        }

        //fixme avoid ambiguous characters
        //fixme check for duplicates
        $entity->share_code = strtoupper(Str::random(config('historiska.token_length.share_code')));
        $entity->is_shared = true;
        $entity->save();

        return SendResponse::success('success', ['code' => $entity->share_code]);
    }

    public function disable(Request $request)
    {
        $user = user::find($request->get('user'));
        $entity = card_entity::find($request->route('entity_id'));

        if (is_null($entity)) {
            return SendResponse::not_found('Invalid entity ID');
        }

        if (!$entity->is_shared) {
            return SendResponse::forbidden('Card is not shared');
        }

        $entity->share_code = null;
        $entity->is_shared = false;
        $entity->save();

        return SendResponse::success('card share disabled');
    }

    public function activate(Request $request)
    {
        $user = user::find($request->get('user'));
        $entity = card_entity
            ::where('share_code', $request->route('share_code'))
            ->where('is_shared', true)
            ->where('owner', '<>', $user->id)
            ->get();

        if ($entity->count() != 1) {
            return SendResponse::not_found('Invalid share code');
        }

        $entity = $entity->first();

        $entity->owner = $user->id;
        $entity->is_shared = false;
        $entity->share_code = null;
        $entity->save();

        $card = card::find($entity->card);

        return SendResponse::success('card activated successfully', [
            'id' => $card->id,
            'is_gold' => $entity->is_gold,
            'name' => $card->name,
            'description' => $card->description,
            'code' => $card->code,
            'birth' => $card->birth,
            'death' => $card->death,
            'img' => $card->img_path,
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
        ]);
    }

}
