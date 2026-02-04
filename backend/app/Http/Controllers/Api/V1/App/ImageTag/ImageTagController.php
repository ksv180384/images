<?php

namespace App\Http\Controllers\Api\V1\App\ImageTag;

use App\Http\Controllers\Controller;
use App\Http\Resources\Admin\Category\CategoryCollection;
use App\Http\Resources\Admin\Category\CategoryResource;
use App\Http\Resources\Admin\Category\ImageCollection;
use App\Http\Resources\Admin\Category\ImageTagCollection;
use App\Http\Resources\App\ImageTag\ImageTagResource;
use App\Services\Category\CategoryService;
use App\Services\Category\ImageService;
use App\Services\Category\ImageTagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Tags')]
class ImageTagController extends Controller
{

    #[OA\Get(
        path: '/image-tags/all',
        summary: 'Получаем все тега изображения',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Успешный ответ со списком категорий',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/AppImageTagResource')
                )
            ),
        ]
    )]
    public function all(ImageTagService $imageTagService)
    {
        $tags = $imageTagService->all();

        return response()->json(ImageTagResource::collection($tags));
    }

}

