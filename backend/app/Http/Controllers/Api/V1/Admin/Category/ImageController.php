<?php

namespace App\Http\Controllers\Api\V1\Admin\Category;

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

    /**
     * Получаем изображение
     */
    public function getById(
        int $categoryId,
        int $imageId,
        ImageService $imageService
    ): JsonResponse
    {
        try {
            $image = $imageService->find($imageId);
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json([
            'data' => ImageResource::make($image)
        ]);
    }

    #[OA\Get(
        path: '/admin/category/{categoryId}/images',
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
    public function getByCategoryId(
        int $categoryId,
        ImageService $imageService
    ): JsonResponse
    {
        try {
            $images = $imageService->getByCategoryId($categoryId);
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

//        return new ImageCollection($images);
        return response()->json(ImageResource::collection($images));
    }

    /**
     * Добавляем изображение
     */
    public function store(
        CreateImageRequest $request,
        ImageService $imageService
    ): ImageResource
    {
        try {
            $image = $imageService->create($request->validated());
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return new ImageResource($image);
    }

    /**
     * Удаляем изображение
     */
    public function delete(
        DeleteImageRequest $request,
        ImageService $imageService
    ): JsonResponse
    {
        try {
            $res = $imageService->delete($request->image_id);
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json(['status' => $res]);
    }

    /**
     * Получаем изображение лица
     */
    public function getFace(Request $request): JsonResponse
    {
        try {
            $image = (new ImageService())->find($request->image_id);
            $resultImage = (new FaceDetectedService($image))->getFace();
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json($resultImage->json());
    }

    /**
     * Определяем координаты лиц на изображении
     */
    public function detectedFacesByImage(
        Request $request,
        ImageService $imageService
    ): JsonResponse
    {
        try {
            $image = $imageService->find($request->image_id);
            $resultDetected = (new FaceDetectedService($image))->detectedFace();
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json($resultDetected->json());
    }

    /**
     * Вырезаем лицо из изображения по координатам
     */
    public function faceCrop(
        Request $request,
        ImageService $imageService
    ): JsonResponse
    {
        try{
            $image = $imageService->find($request->image_id);
            $result = (new FaceDetectedService($image))->saveDetectedFace($request->position);
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json($result->json());
    }

    /**
     * Поиск по фотографии
     */
    public function searchByPhoto(Request $request)
    {

        try {
            $result = (new FaceDetectedService())->searchByPhoto($request->category_id, $request->img_base64);
            $resImages = $result->collect();

            $imagesPaths = $resImages->map(function($image){
                return $image['path'];
            });

            $images = (new ImageService())->getImagesIn($imagesPaths->toArray());

            $images = $images->map(function ($image) use ($resImages) {
                $p = $resImages->firstWhere('path', $image->image);
                $image->similarity = $p['similarity'];
                return $image;
            });
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return new ImageCollection($images->sortByDesc('similarity'));
    }

    /**
     * Прикрепляем теги к картинке
     */
    public function attachToImage(
        AttachTagsToImageRequest $request,
        ImageService $imageService
    ): ImageResource
    {
        try {
            $image = $imageService->attachTags(
                $request->validated('image_id'),
                $request->validated('tags')
            );
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return new ImageResource($image);
    }

    /**
     * Сохраняем дату создания изображения (когда было сделано фото)
     */
    public function saveCreateDate(
        UpdateImageDateRequest $request,
        ImageService $imageService
    ): ImageResource
    {
        try {
            $image = $imageService->updateDateCreateImage($request->validated());
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return new ImageResource($image);
    }

    /**
     * Получаем изображения с учетом фильтра
     */
    public function filter(
        Request $request,
        ImageService $imageService,
        ImagesFilter $imagesFilter
    ): ImageCollection
    {
        $images = $imageService->getByCategoryFilter($request->category_id, $imagesFilter);
        return new ImageCollection($images);
    }

    /**
     * Получаем похожие изображение
     */
    public function similar(
        int $id,
        ImageService $imageService
    ): ImageCollection
    {
        $images = $imageService->similar($id);

        return new ImageCollection($images);
    }

    public function getImageMetadata($id)
    {
        $image = (new ImageService())->find($id);
        $resultImage = (new FaceDetectedService($image))->getImageMetadata();

//        dd($resultImage);
    }

    public function saveFormat($imageId, ImageService $imageService)
    {
        $format = 512;
        $image = $imageService->find($imageId);
        $img = Image::make(Storage::disk('public')->path($image->image));

        $size = $image->width > $image->height ? $image->width : $image->height;

        // Меняем размер холста на равностороний, за основу берем большую чторону
        $img->resizeCanvas($size, $size);

        // измените размер изображения так, чтобы самая большая сторона вписывалась в ограничение; меньшая
        // сторона будет масштабирована для сохранения исходного соотношения сторон
        $img->resize($format, $format, function ($constraint) {
            $constraint->aspectRatio();
            $constraint->upsize();
        });
        $path = 'categories/' . $image->category_id . '/images/format/';
        if(!Storage::disk('public')->exists($path)){
            Storage::disk('public')->makeDirectory($path, 0777, true, true);
        }
        $file = pathinfo($image->image);
        $img->save(
            Storage::disk('public')->path($path.$file['filename'] . '_512.' . $file['extension'])
        );
    }
}
