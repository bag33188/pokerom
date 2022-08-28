<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\Game */
class GameResource extends JsonResource
{
    public $additional = ['success' => true];
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'id' => $this->id,
            'game_name' => $this->game_name,
            'game_type' => $this->game_type,
            'date_released' => $this->date_released->format('Y-m-d'),
            'generation' => $this->generation,
            'region' => $this->region,
            'slug' => $this->slug,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,

            'rom_id' => $this->rom_id,
            'rom' => new RomResource($this->whenLoaded('rom')),
        ];
    }
}
