<?php

namespace App\Http\Controllers;

use App\Custom\SendResponse;
use App\Models\card;
use App\Models\card_entity;
use App\Models\category;
use App\Models\continent;
use App\Models\country;
use Illuminate\Http\Request;

class CardController extends Controller
{
    protected function get_all_cards(int $user_id = null): array
    {
        $result = [];
        foreach (card::all() as $card) {
            $country = country::find($card->country);
            $category = category::find($card->category);
            $continent = continent::find($country->continent);

            $current = [
                'id' => $card->id,
                'name' => $card->name,
                'description' => $card->description,
                'code' => $card->code,
                'birth' => $card->birth,
                'death' => $card->death,
                'img' => $card->img_path,
                'category' => [
                    'id' => $category->id,
                    'name' => $category->name,
                ],
                'country' => [
                    'id' => $country->id,
                    'name' => $country->name,
                ],
                'continent' => [
                    'id' => $continent->id,
                    'name' => $continent->name,
                ],
            ];

            if (!is_null($user_id)) {
                $current['quantity'] = card_entity
                    ::where('card', $card->id)
                    ->where('owner', $user_id)
                    ->where('is_gold', false)
                    ->count();
                $current['is_gold'] = false;

                $nb_gold = card_entity
                    ::where('card', $card->id)
                    ->where('owner', $user_id)
                    ->where('is_gold', true)
                    ->count();

                if ($nb_gold > 0) {
                    $current_gold = $current;
                    $current_gold['is_gold'] = true;
                    $current_gold['quantity'] = $nb_gold;
                    array_push($result, $current_gold);
                }
            }

            array_push($result, $current);
        }
        return $result;
    }

    public function get_all_entities(int $user_id): ?array
    {
        $result = [];
        foreach (card_entity::where('owner', $user_id) as $entity) {
            $current = [
                'entity_id' => $entity->id,
                'is_shared' => $entity->is_shared,
                'share_code' => $entity->share_code,
                'is_gold' => $entity->is_gold,
            ];
            $result[$entity->card] = $current;
        }
        return $result;
    }

    public function list_all(Request $request)
    {
        return SendResponse::success('success', ['cards' => $this->get_all_cards()]);
    }

    public function get_collection(Request $request)
    {

    }
}
