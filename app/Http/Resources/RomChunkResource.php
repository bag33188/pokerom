<?php

namespace App\Http\Resources;

use App\Models\RomChunk;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RomChunk */
class RomChunkResource extends JsonResource
{
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'n' => $this->n,
            'data' => $this->data,

            'files_id' => (string)$this->files_id,
            '_id' => (string)$this->_id,

            'romFile' => new RomFileResource($this->whenLoaded('romFile')),
        ];
    }
}
