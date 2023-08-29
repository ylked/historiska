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
    protected function get_all_cards(): array
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
            array_push($result, $current);
        }
        return $result;
    }

    public function get_all_entities(int $user_id): ?array
    {
        foreach (card_entity::where('owner', $user_id) as $entity) {

        }
        return null;
    }

    public function list_all(Request $request)
    {
        return SendResponse::success('success', ['cards' => $this->get_all_cards()]);
    }

    public function get_collection(Request $request)
    {

    }
}
