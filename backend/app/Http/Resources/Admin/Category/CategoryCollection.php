<?php

namespace App\Http\Resources\Admin\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use OpenApi\Attributes as OA;

class CategoryCollection extends ResourceCollection
{

    #[OA\Schema(
        schema: 'AdminCategoryCollection',
        title: 'CategoryCollection',
        description: 'Список категорий',
        properties: [
            new OA\Property(
                property: 'data',
                type: 'array',
                items: new OA\Items(
                    ref: '#/components/schemas/AdminCategoryResource',
                    type: 'object',
                )
            )
        ]
    )]
    public function toArray(Request $request): array
    {
        //return parent::toArray($request);
        return $this->collection->toArray();
    }
}
