<?php

namespace App\Http\Requests\Admin\Category;

use Illuminate\Foundation\Http\FormRequest;

/**
 * @property string $title
 * @property string $description
 * @property string $url
 */
/**
 * @OA\Schema(
 *     schema="AdminCreateCategoryRequest",
 *     required={"title", "description", "url"},
 *     @OA\Property(
 *         property="title",
 *         type="string",
 *         description="Название категории",
 *         example="Электроника",
 *         maxLength=255
 *     ),
 *     @OA\Property(
 *         property="description",
 *         type="string",
 *         description="Описание категории",
 *         example="Категория электронных товаров"
 *     ),
 *     @OA\Property(
 *         property="url",
 *         type="string",
 *         description="URL-адрес категории",
 *         format="uri",
 *         example="https://example.com/category/electronics",
 *         maxLength=500
 *     )
 * )
 */
class CreateCategoryRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'title' => 'required',
            'description' => 'required',
            'url' => 'required|url',
        ];
    }
}
