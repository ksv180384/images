<?php

namespace App\Http\Controllers\Api\V1\App\Image;

use App\Http\Controllers\Controller;
use App\Http\Resources\App\Image\ImageItemResource;
use App\Http\Resources\App\Image\ImageResource;
use App\Services\Category\ImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Images')]
class ImageController extends Controller
{
    #[OA\Get(
        path: '/category/{categoryId}/images',
        summary: 'Получаем изображения категории',
        parameters: [
            new OA\Parameter(
                name: 'categoryId',
                description: 'ID категории',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/AppImageResource')
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Error message')
                    ]
                )
            )
        ]
    )]
    public function getImagesByCategoryId(
        int $categoryId,
        ImageService $imageService
    ): JsonResponse
    {
        try {
            $images = $imageService->getByCategoryId($categoryId);

        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json(ImageResource::collection($images));
    }

    #[OA\Get(
        path: '/category/{categoryId}/images/{imageId}',
        summary: 'Получаем изображения категории',
        parameters: [
            new OA\Parameter(
                name: 'categoryId',
                description: 'ID категории',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            ),
            new OA\Parameter(
                name: 'imageId',
                description: 'ID изображения',
                in: 'path',
                required: true,
                schema: new OA\Schema(type: 'integer')
            )
        ],
        responses: [
            new OA\Response(
                response: 200,
                description: 'Successful operation',
                content: new OA\JsonContent(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/AppImageItemResource')
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Validation error',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(property: 'message', type: 'string', example: 'Error message')
                    ]
                )
            )
        ]
    )]
    public function show(int $id, ImageService $imageService): JsonResponse
    {
        $image = $imageService->find($id);

        return response()->json(ImageItemResource::make($image));
    }
}
