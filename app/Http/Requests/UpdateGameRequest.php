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
use App\Rules\RequiredIfPutRequest;
use Date;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class UpdateGameRequest extends FormRequest
{
    public function __construct(private readonly RequiredIfPutRequest $requiredIfPutRequest)
    {
        parent::__construct();
    }

    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize(): bool
    {
        $game = Game::find($this->route('game') ?: $this->route('gameId'));
        return $this->user()->can('update', $game);
    }

    public function prepareForValidation()
    {
        $this->merge([
            'slug' => Str::slug($this->input('game_name')),
            'date_released' => Date::create($this->input('date_released'))->format('Y-m-d'),
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
            'game_name' => [$this->requiredIfPutRequest, 'string', new MinLengthRule(MIN_GAME_NAME_LENGTH), new MaxLengthRule(MAX_GAME_NAME_LENGTH), new GameNameRule()],
            'game_type' => [$this->requiredIfPutRequest, 'string', new MinLengthRule(MIN_GAME_TYPE_LENGTH), new MaxLengthRule(MAX_GAME_TYPE_LENGTH), new GameTypeRule()],
            'region' => [$this->requiredIfPutRequest, 'string', new MinLengthRule(MIN_GAME_REGION_LENGTH), new MaxLengthRule(MAX_GAME_REGION_LENGTH), new GameRegionRule()],
            'date_released' => [$this->requiredIfPutRequest, 'date', 'after_or_equal:1996-02-27', 'date_format:Y-m-d'],
            'generation' => [$this->requiredIfPutRequest, 'integer', new MinSizeRule(MIN_GAME_GENERATION_VALUE), new MaxSizeRule(MAX_GAME_GENERATION_VALUE)],
            'slug' => [Rule::unique('games', 'slug')->ignore($this->route('game'))->ignore($this->route('gameId'))],
        ];
    }
}
