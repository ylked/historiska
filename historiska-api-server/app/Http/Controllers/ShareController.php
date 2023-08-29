<?php

namespace App\Http\Controllers;

use App\Custom\SendResponse;
use App\Models\card;
use App\Models\card_entity;
use App\Models\user;
use Illuminate\Http\Request;

class ShareController extends Controller
{
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
        $result['is_more_shareable'] = $entities
                ->where('is_shared', false)
                ->where('is_gold', false)
                ->count() >= 2;

        $result['is_more_shareable_gold'] = $entities
                ->where('is_shared', false)
                ->where('is_gold', true)
                ->count() >= 2;

        foreach ($entities as $entity) {
            $result['entities'][] = [
                'id' => $entity->id,
                'is_shared' => boolval($entity->is_shared),
                'code' => $entity->share_code,
            ];
        }

        return SendResponse::success('success', $result);
    }
}
