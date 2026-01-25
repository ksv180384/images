<?php

namespace App\Http\Resources\Admin\Category;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'ImageResource',
    title: 'Image Resource',
    description: 'Image resource representation'
)]
class ImageResource extends JsonResource
{
    #[OA\Property(property: 'id', description: 'Идентификатор изображения', type: 'integer', example: 1)]
    #[OA\Property(property: 'category_id', description: 'Идентификатор категории', type: 'integer', example: 2)]
    #[OA\Property(property: 'image', description: 'путь к изображению', type: 'string', example: 'path/image.jpg')]
    #[OA\Property(property: 'image_preview', description: 'путь к превью изображения', type: 'string', example: 'path/image.jpg')]
    #[OA\Property(property: 'title', description: 'Название изображения', type: 'string', example: 'Название изображения')]
    #[OA\Property(property: 'description', description: 'Описание изображения', type: 'string', example: 'Описание изображения')]
    #[OA\Property(property: 'image_created_at', description: 'Дата когда было сделано фото', type: 'string', example: '2022-07-11')]
    #[OA\Property(property: 'width', description: 'Ширина изображения', type: 'string', example: '1024')]
    #[OA\Property(property: 'height', description: 'высота изображения', type: 'string', example: '1024')]
    #[OA\Property(property: 'path_full', description: 'Полный путь до изображения', type: 'string', example: 'https://site.ru/path/image.jpg')]
    #[OA\Property(property: 'path_preview_full', description: 'Полный путь к превью изображения', type: 'string', example: 'https://site.ru/path/image.jpg')]
    #[OA\Property(property: 'path_face', description: 'Ссылка на изображение с лицом', type: 'string', example: 'path/image.jpg')]
    #[OA\Property(property: 'is_features', description: 'Есть ли файл с фрагментами для поиска по изображениям', type: 'boolean', example: true)]
    #[OA\Property(property: 'created_at', description: 'дата добавления', type: 'string', example: '2022-07-11')]
    #[OA\Property(property: 'updated_at', description: 'дата обновления', type: 'string', example: '2022-07-11')]
    #[OA\Property(
        property: 'tags',
        description: 'теги изображения',
        type: 'array',
        items: new OA\Items(
            properties: [
                new OA\Property(property: 'id', type: 'integer'),
                new OA\Property(property: 'title', type: 'string')
            ],
            type: 'object'
        ),
        example: [['id' => 1, 'title' => 'tag1'], ['id' => 2, 'title' => 'tag2']]
    )]
    public function toArray(Request $request): array
    {
        $result =  [
            'id' => $this->id,
            'category_id' => $this->category_id,
            'image' => $this->image,
            'image_preview' => $this->image_preview,
            'title' => $this->title,
            'description' => $this->description,
            'image_created_at' => optional($this->image_created_at)->format('Y-m-d'),
            'width' => $this->width,
            'height' => $this->height,
            'path_full' => $this->path_full,
            'path_preview_full' => $this->path_preview_full,
            'path_face' => $this->path_face,
            'is_features' => $this->is_features,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        if($this->tags){
            $result['tags'] = $this->tags->map(function ($item) {
                return $item->only(['id', 'title']);
            });
        }

        return $result;
    }
}
