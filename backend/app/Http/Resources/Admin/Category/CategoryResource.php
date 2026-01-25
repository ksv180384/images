<?php

namespace App\Http\Resources\Admin\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'AdminCategoryResource',
    properties: [
        new OA\Property(property: 'id', type: 'number', example: '1'),
        new OA\Property(property: 'title', type: 'string', example: 'Название категории'),
        new OA\Property(property: 'url', type: 'string', example: 'https://i.pinimg.com/originals/19/89/44/198944ea9f57d70ea850fc868efbd4b6.jpg'),
        new OA\Property(property: 'description', type: 'string', example: 'Описание категории'),
    ],
    type: 'object'
)]
class CategoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'url' => $this->url,
            'description' => $this->description,
        ];
    }
}
