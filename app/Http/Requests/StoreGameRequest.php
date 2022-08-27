<?php

namespace App\Http\Requests;

use App\Models\Game;
use App\Rules\GameNameRule;
use App\Rules\GameRegionRule;
use App\Rules\GameTypeRule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;

class StoreGameRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return $this->user()->can('create', Game::class);
    }

    public function prepareForValidation()
    {
        $this->merge([
//            'slug' => Str::slug($this->input('game_name')),
            'slug' => Str::slug($this->game_name),
            'date_released' => \Date::create($this->date_released)->format('Y-m-d'),
        ]);
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules()
    {
        return [
            'game_name' => ['required', 'string', 'max:' . MIN_GAME_NAME_LENGTH, 'max:' . MAX_GAME_NAME_LENGTH, new GameNameRule()],
            'game_type' => ['required', 'string', 'min:' . MIN_GAME_TYPE_LENGTH, 'max:' . MAX_GAME_TYPE_LENGTH, new GameTypeRule()],
            'region' => ['required', 'string', 'max:' . MAX_GAME_REGION_LENGTH, 'min:' . MIN_GAME_REGION_LENGTH, new GameRegionRule()],
            'date_released' => ['required', 'date', 'after_or_equal:1996-02-27', 'date_format:Y-m-d'],
            'generation' => ['required', 'integer', 'min:0', 'max:9'],
        ];
    }
}
