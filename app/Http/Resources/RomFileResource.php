<?php

namespace App\Http\Resources;

use App\Models\RomFile;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin RomFile */
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
