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

/** @mixin Game */
class StoreGameRequest extends FormRequest
{
    private string|int|null $romId = null;

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        return $this->user()->can('create', Game::class);
    }

    public function prepareForValidation(): void
    {
        $this->mergeIfMissing([
            'rom_id' => $this->romId,
        ]);

        $this->romId = $this->query('rom_id') ?? $this->input('rom_id');

        $this->merge([
            'date_released' => Date::create($this->input('date_released'))->format('Y-m-d'),
            'game_name' => preg_replace(EACUTE_PATTERN, 'e', $this->input('game_name')),
            'rom_id' => (int)$this->romId,
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
            'game_name' => ['required', 'string', new MinLengthRule(MIN_GAME_NAME_LENGTH), new MaxLengthRule(MAX_GAME_NAME_LENGTH), new GameNameRule()],
            'game_type' => ['required', 'string', new MinLengthRule(MIN_GAME_TYPE_LENGTH), new MaxLengthRule(MAX_GAME_TYPE_LENGTH), new GameTypeRule()],
            'region' => ['required', 'string', new MinLengthRule(MIN_GAME_REGION_LENGTH), new MaxLengthRule(MAX_GAME_REGION_LENGTH), new GameRegionRule()],
            'date_released' => ['required', 'date', 'after_or_equal:' . FIRST_POKEMON_GAME_RELEASE_DATE, 'date_format:Y-m-d'],
            'generation' => ['required', 'integer', new MinSizeRule(MIN_GAME_GENERATION_VALUE), new MaxSizeRule(MAX_GAME_GENERATION_VALUE)],
            'rom_id' => ['required', 'integer', Rule::exists('roms', 'id'), Rule::unique('games', 'rom_id')],
            'slug' => 'string|unique:games,slug',
            # 'rom_id' => 'required|integer|exists:roms,id|unique:games,rom_id'
        ];
    }

    public function messages(): array
    {
        return [
            'slug.unique' => "The game name {$this->input('game_name')} already exists.",
            'rom_id.unique' => "The ROM with id of {$this->romId} already has a game.",
        ];
    }
}
