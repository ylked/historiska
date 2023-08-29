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
    protected function get_all_cards(int $user_id = null, int $cat = null): array
    {
        $result = [];
        foreach (card::all() as $card) {
            if (is_null($cat) or ($card->category == $cat)) {
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
        }
        return $result;
    }

    public function get_all_entities(int $user_id, int $cat = null): array
    {
        $result = [];

        foreach (card::all() as $card) {
            $current_card = [];
            $entities = card_entity::where('owner', $user_id)->where('card', $card->id)->get();

            if ((
                    is_null($cat) or ($card->category == $cat)
                )
                and (
                    $entities->count() > 0
                )) {
                foreach ($entities as $entity) {
                    $current_entity = [
                        'entity_id' => $entity->id,
                        'is_shared' => boolval($entity->is_shared),
                        'share_code' => $entity->share_code,
                        'is_gold' => boolval($entity->is_gold),
                    ];

                    $current_card[] = $current_entity;
                }
                $result[$card->id] = $current_card;
            }
        }
        return $result;
    }

    public function list_all(Request $request)
    {
        return SendResponse::success('success', ['cards' => $this->get_all_cards()]);
    }

    public function get_collection(Request $request)
    {
        return SendResponse::success('success', [
            'cards' => $this->get_all_cards($request->get('user')),
            'entities' => $this->get_all_entities($request->get('user'))
        ]);
    }

    public function get_entities_of_card(Request $request)
    {
        $card = $request->route('card_id');
        $entities = card_entity
            ::where('card', $card)
            ->where('owner', $request->get('user'));

        if ($entities->count() == 0) {
            return SendResponse::forbidden('Card is not owned by user');
        }

        $result = [];
        foreach ($entities->get() as $entity) {
            $current = [
                'entity_id' => $entity->id,
                'is_shared' => boolval($entity->is_shared),
                'is_gold' => boolval($entity->is_gold),
                'share_code' => $entity->share_code
            ];
            array_push($result, $current);
        }

        return SendResponse::success('success', $result);
    }

    public function get_categories(Request $request)
    {
        $result = [];
        foreach (category::all() as $category) {
            $total_qty = card::where('category', $category->id)->count();

            if ($total_qty > 0) {
                $owned_qty = card_entity
                    ::select('card.id')
                    ->join('card', 'card.id', '=', 'card_entity.card')
                    ->where('card.category', $category->id)
                    ->where('card_entity.owner', $request->get('user'))
                    ->get()
                    ->unique()
                    ->count();

                $current = [
                    'id' => $category->id,
                    'name' => $category->name,
                    'owned_qty' => $owned_qty,
                    'total_qty' => $total_qty,
                    'completion_percent' => $owned_qty / $total_qty * 100
                ];

                array_push($result, $current);
            }
        }

        return SendResponse::success('success', $result);
    }

    public function get_collection_filter_by_category(Request $request)
    {
        $category = category::find($request->route('category_id'));

        if (is_null($category)) {
            return SendResponse::not_found('Invalid category ID');
        }

        return SendResponse::success('success', [
            'cards' => $this->get_all_cards($request->get('user'), $category->id),
            'entities' => $this->get_all_entities($request->get('user'), $category->id)
        ]);
    }
}
