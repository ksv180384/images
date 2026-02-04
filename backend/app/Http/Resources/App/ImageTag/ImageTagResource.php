<?php

namespace App\Http\Resources\App\ImageTag;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AppImageTagResource',
    properties: [
        new OA\Property(property: 'id', type: 'number', example: '1'),
        new OA\Property(property: 'title', type: 'string', example: 'Название тега'),
    ],
    type: 'object'
)]
class ImageTagResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
        ];
    }
}
