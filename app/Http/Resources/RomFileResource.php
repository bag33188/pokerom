<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin \App\Models\RomFile */
class RomFileResource extends JsonResource
{
    public $additional = ['success' => true];
    /**
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        return [
            'chunkSize' => $this->chunkSize,
            'filename' => $this->filename,
            'length' => $this->length,
            'uploadDate' => $this->uploadDate,
            'md5' => $this->md5,

            '_id' => $this->_id,

            'rom' => new RomResource($this->whenLoaded('rom')),
        ];
    }
}
