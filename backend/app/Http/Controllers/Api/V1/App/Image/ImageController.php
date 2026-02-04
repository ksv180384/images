<?php

namespace App\Http\Controllers\Api\V1\App\Image;

use App\Http\Controllers\Controller;
use App\Http\Filters\ImagesFilter;
use App\Http\Requests\Admin\Image\CreateImageRequest;
use App\Http\Requests\Admin\Image\DeleteImageRequest;
use App\Http\Requests\Admin\Image\UpdateImageDateRequest;
use App\Http\Requests\Admin\Tag\AttachTagsToImageRequest;
use App\Http\Resources\Admin\Category\ImageCollection;
use App\Http\Resources\Admin\Category\ImageResource;
use App\Services\Category\ImageService;
use App\Services\FaceDetected\FaceDetectedService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;
use Intervention\Image\Facades\Image;
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
                    items: new OA\Items(ref: '#/components/schemas/ImageResource')
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

}
