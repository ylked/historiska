<?php

namespace App\Http\Controllers;

use App\Custom\SendResponse;
use App\Models\card;
use App\Models\category;
use App\Models\continent;
use App\Models\country;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    protected function get_id_of_field(string $table, string $field_name, int|float|string $value, string $field_2 = null, int|float|string $value_2 = null): int
    {
        $result = $table::where($field_name, $value)->get();

        if ($result->count() == 0) {
            $result = new $table;
            $result->$field_name = $value;

            if ($field_2 != null) {
                $result->$field_2 = $value_2;
            }
            $result->save();
            $result = $table::where($field_name, $value)->first();
            return $result->id;
        } else {
            return $result->first()->id;
        }
    }

    public function create_card(Request $request)
    {
        $param = $request->json()->all();

        if (card::where('name', $param['name'])->get()->count() != 0) {
            return SendResponse::forbidden('A card with the same name already exists');
        }

        $continent_id = $this->get_id_of_field(continent::class, 'name', $param['continent']);
        $country_id = $this->get_id_of_field(country::class, 'name', $param['country'], 'continent', $continent_id);
        $category_id = $this->get_id_of_field(category::class, 'name', $param['category']);

        $card = new card();
        $card->name = $param['name'];
        $card->description = $param['description'];
        $card->rarity = $param['rarity'];
        $card->code = $param['code'];
        $card->birth = $param['birth'];
        $card->death = $param['death'];
        $card->img_path = $param['img'];
        $card->country = $country_id;
        $card->category = $category_id;

        $card->save();

        return SendResponse::success('Card successfully created', ["id" => card::where('name', $card->name)->first()->id]);
    }
}
