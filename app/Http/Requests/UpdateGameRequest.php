<?php

namespace App\Http\Requests;

use App\Models\Game;
use App\Rules\GameNameRule;
use App\Rules\GameRegionRule;
use App\Rules\GameTypeRule;
use App\Rules\MaxLengthRule;
use App\Rules\MaxSizeRule;
use App\Rules\MinLengthRule;
use App\Rules\MinSizeRule;
use Date;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateGameRequest extends FormRequest
{
    private string $routeParamName;

    function __construct()
    {
        parent::__construct();

        $this->routeParamName = $this->routeIs('api.*') ? 'gameId' : 'game';
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $game = Game::find($this->route($this->routeParamName));
        return $this->user()->can('update', $game);
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->input('game_name')),
            'date_released' => Date::create($this->input('date_released'))->format('Y-m-d'),
            'game_name' => preg_replace("/[\x{E9}\x{C9}]/u", "\u{0065}", $this->input('game_name'))
        ]);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'game_name' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_GAME_NAME_LENGTH), new MaxLengthRule(MAX_GAME_NAME_LENGTH), new GameNameRule()],
            'game_type' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_GAME_TYPE_LENGTH), new MaxLengthRule(MAX_GAME_TYPE_LENGTH), new GameTypeRule()],
            'region' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_GAME_REGION_LENGTH), new MaxLengthRule(MAX_GAME_REGION_LENGTH), new GameRegionRule()],
            'date_released' => [Rule::requiredIf($this->isMethod('PUT')), 'date', 'after_or_equal:' . FIRST_POKEMON_GAME_RELEASE_DATE, 'date_format:Y-m-d'],
            'generation' => [Rule::requiredIf($this->isMethod('PUT')), 'integer', new MinSizeRule(MIN_GAME_GENERATION_VALUE), new MaxSizeRule(MAX_GAME_GENERATION_VALUE)],
            'slug' => ['string', Rule::unique('games', 'slug')->ignore($this->route($this->routeParamName))]
        ];
    }
}
