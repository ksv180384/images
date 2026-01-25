<?php

namespace App\Http\Controllers\Api\V1\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Tag\CreateImageTagRequest;
use App\Http\Resources\Admin\Category\CategoryCollection;
use App\Http\Resources\Admin\Category\ImageTagCollection;
use App\Http\Resources\Admin\Category\ImageTagResource;
use App\Services\Category\CategoryService;
use App\Services\Category\ImageService;
use App\Services\Category\ImageTagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class ImageTagController extends Controller
{
    /**
     * Получаем данные для страницы с тегами
     * @param ImageTagService $imageTagService
     * @param CategoryService $categoryService
     * @return \Illuminate\Http\JsonResponse
     * @throws \Exception
     */
    public function tagsPage(
        ImageTagService $imageTagService,
        CategoryService $categoryService
    ): JsonResponse
    {
        try {
            $tags = $imageTagService->all();
            $categories = $categoryService->all();
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json([
            'data' => [
                'tags' => new ImageTagCollection($tags),
                'categories' => new CategoryCollection($categories)
            ]
        ]);
    }

    /**
     * Получаем все теги
     * @param ImageTagService $imageTagService
     * @return ImageTagCollection
     */
    public function all(ImageTagService $imageTagService): ImageTagCollection
    {
        try {
            $tags = $imageTagService->all();
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return new ImageTagCollection($tags);
    }

    /**
     * Получаем теги прикрепленные к изображению
     * @param $image_id
     * @param ImageTagService $imageTagService
     * @return ImageTagCollection
     */
    public function getByImageId(
        $image_id,
        ImageTagService $imageTagService
    ): ImageTagCollection
    {
        try {
            $tags = $imageTagService->getByImageId($image_id);
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return new ImageTagCollection($tags);
    }

    /**
     * Добавляем новый тег
     * @param CreateImageTagRequest $request
     * @param ImageTagService $imageTagService
     * @return ImageTagResource
     */
    public function store(
        CreateImageTagRequest $request,
        ImageTagService $imageTagService
    ): ImageTagResource
    {
        try {
            $image = $imageTagService->create($request->validated());
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return new ImageTagResource($image);
    }

    /**
     * Удаляем тег
     * @param $id
     * @param ImageTagService $imageTagService
     * @return JsonResponse
     */
    public function delete($id, ImageTagService $imageTagService): JsonResponse
    {
        try {
            $imageTagService->delete($id);
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json(['message' => 'Тег успешно удален.']);
    }

    /**
     * Получаем идентификаторы изображений прикркплнных к тегам
     * @param Request $request
     * @param ImageService $imageService
     * @return JsonResponse
     */
    public function imagesByTagIds(
        Request $request,
        ImageService $imageService
    ): JsonResponse
    {
        try {
            $imagesIds = $imageService->getImagesByCategoryIdAndTagsIds(
                $request->category_id,
                $request->tags_ids
            )->pluck('id');
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json(['images_ids' => $imagesIds]);
    }

    /**
     * Прикрепляем теги к изобоажениям
     * @param Request $request
     * @param ImageTagService $imageTagService
     * @return JsonResponse
     */
    public function attachImages(
        Request $request,
        ImageTagService $imageTagService
    ): JsonResponse
    {
        try {
            $imageTagService->attachImages($request->tags_ids, $request->images_ids);
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return response()->json(['message' => 'ok']);
    }
}
