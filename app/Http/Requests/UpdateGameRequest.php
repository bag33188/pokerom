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
use Illuminate\Validation\Rule;

class UpdateGameRequest extends FormRequest
{
    private readonly string $routeParamName;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $this->routeParamName = $this->routeIs('api.*') ? 'gameId' : 'game';;

        $game = Game::find($this->route($this->routeParamName));
        return $this->user()->can('update', $game);
    }

    public function prepareForValidation(): void
    {
        $normalizeGameNameUnicodeChars = fn(?string $gameName = '') => preg_replace("/[\x{E9}\x{C9}]/u", "e", $gameName);
        $formatDateReleased = fn(?string $dateReleased = '') => Date::create($dateReleased)->format('Y-m-d');

        $fieldsToMerge = array();

        if ($this->isMethod('PATCH')) {

            if ($this->has('game_name')) {
                $fieldsToMerge['game_name'] = $normalizeGameNameUnicodeChars($this->input('game_name'));
            }

            if ($this->has('date_released')) {
                $fieldsToMerge['date_released'] = $formatDateReleased($this->input('date_released'));
            }
        } else {
            $fieldsToMerge = [
                'date_released' => $formatDateReleased($this->input('date_released')),
                'game_name' => $normalizeGameNameUnicodeChars($this->input('game_name')),
            ];
        }

        $this->merge($fieldsToMerge);
    }


    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        $pokemonGreenReleaseDate = FIRST_POKEMON_GAME_RELEASE_DATE;
        return [
            'game_name' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_GAME_NAME_LENGTH), new MaxLengthRule(MAX_GAME_NAME_LENGTH), new GameNameRule()],
            'game_type' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_GAME_TYPE_LENGTH), new MaxLengthRule(MAX_GAME_TYPE_LENGTH), new GameTypeRule()],
            'region' => [Rule::requiredIf($this->isMethod('PUT')), 'string', new MinLengthRule(MIN_GAME_REGION_LENGTH), new MaxLengthRule(MAX_GAME_REGION_LENGTH), new GameRegionRule()],
            'date_released' => [Rule::requiredIf($this->isMethod('PUT')), 'date', "after_or_equal:$pokemonGreenReleaseDate", 'date_format:Y-m-d'],
            'generation' => [Rule::requiredIf($this->isMethod('PUT')), 'integer', new MinSizeRule(MIN_GAME_GENERATION_VALUE), new MaxSizeRule(MAX_GAME_GENERATION_VALUE)],
            'slug' => ['string', Rule::unique('games', 'slug')->ignore($this->route($this->routeParamName), 'id')],
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => "The game name {$this->input('game_name')} already exists.",
        ];
    }
}
