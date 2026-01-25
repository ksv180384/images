<?php

namespace App\Http\Controllers\Api\V1\Admin\Category;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Category\CreateCategoryRequest;
use App\Http\Requests\Admin\Category\UpdateCategoryRequest;
use App\Http\Resources\Admin\Category\CategoryCollection;
use App\Http\Resources\Admin\Category\CategoryResource;
use App\Http\Resources\Admin\Category\ImageCollection;
use App\Http\Resources\Admin\Category\ImageTagCollection;
use App\Services\Category\CategoryService;
use App\Services\Category\ImageService;
use App\Services\Category\ImageTagService;
use Illuminate\Http\JsonResponse;
use Illuminate\Validation\ValidationException;
use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Categories')]
class CategoryController extends Controller
{
    #[OA\Get(
        path: '/admin/categories',
        summary: 'Получаем категории',
        responses: [
            new OA\Response(
                response: 200,
                description: 'Получаем категории',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'object',
                            properties: [
                                new OA\Property(
                                    property: 'categories',
                                    type: 'array',
                                    description: 'Массив категорий',
                                    items: new OA\Items(ref: '#/components/schemas/AdminCategoryResource')
                                )
                            ]
                        )
                    ],
                )
            )
        ]
    )]
    public function index(CategoryService $categoryService): JsonResponse
    {
        try {
            $categories = $categoryService->all();

            return response()->json([
                'data' => [
                    'categories' => CategoryResource::collection($categories),
                ]
            ]);

        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }
    }

    #[OA\Post(
        path: 'admin/category/store',
        summary: 'Добавляем категорию',
        description: 'Создание новой категории с указанием названия, описания и URL',
        requestBody: new OA\RequestBody(
            description: 'Данные для создания категории',
            required: true,
            content: new OA\JsonContent(
                ref: '#/components/schemas/AdminCreateCategoryRequest'
            )
        ),
        responses: [
            new OA\Response(
                response: 200,
                description: 'Категория успешно создана',
                content: new OA\JsonContent(
                    ref: '#/components/schemas/AdminCategoryResource'
                )
            ),
            new OA\Response(
                response: 422,
                description: 'Ошибка валидации'
            ),
        ]
    )]
    public function store(
        CreateCategoryRequest $request,
        CategoryService $categoryService,
    ): CategoryResource
    {
        try {

            $category = $categoryService->create($request->validated());

            return response()->json(CategoryResource::make($category));

        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }
    }

    /**
     * Получаем данные карточки
     * @param int $id
     * @param CategoryService $categoryService
     * @param ImageService $imageService
     * @return JsonResponse
     * @throws \Exception
     */
    public function edit(
        int $id,
        CategoryService $categoryService,
        ImageService $imageService,
        ImageTagService $imageTagService
    ): JsonResponse
    {
        try {
            $category = $categoryService->find($id);
            $images = $imageService->getByCategoryId($category->id);
            $imageTags = $imageTagService->all();
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }
        return response()->json([
            'data' => [
                'category' => new CategoryResource($category),
                'categories' => new CategoryResource($category),
                'images' => new ImageCollection($images),
                'tags' => new ImageTagCollection($imageTags),
            ]
        ]);
    }

    /**
     * Редактируем карточку
     * @param int $id
     * @param UpdateCategoryRequest $request
     * @param CategoryService $categoryService
     * @return CategoryResource
     * @throws ValidationException
     */
    public function update(
        UpdateCategoryRequest $request,
        CategoryService $categoryService
    ): CategoryResource
    {
        try {
            $category = $categoryService->update($request->validated());
        } catch (\Exception $exception){
            throw ValidationException::withMessages(['message' => $exception->getMessage()]);
        }

        return new CategoryResource($category);
    }
}

