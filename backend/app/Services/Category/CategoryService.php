<?php
namespace App\Services\Category;

use App\Models\Category\Category;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class CategoryService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new Category();
    }

    /**
     * Добавляем новую категорию
     * @param array $categoryData
     * @return Category
     * @throws \Exception
     */
    public function create(array $categoryData): Category
    {
        try {
            $category = DB::transaction(function() use ($categoryData) {
                $category = $this->model->create($categoryData);

                return $category;
            }, 3);
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка добавления карточки.');
        }

        return $category;
    }

    /**
     * Редактируем категорию
     * @param array $categoryData
     * @return Category
     * @throws \Exception
     */
    public function update(array $categoryData): Category
    {
        try {
            $category = DB::transaction(function() use ($categoryData) {
                $category = $this->model->find($categoryData['id']);
                $category->update([
                    'title' => $categoryData['title'],
                    'description' => $categoryData['description'],
                    'url' => $categoryData['url'],
                ]);

                $category->fresh();

                return $category;
            }, 3);
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка при обновлении карточки.');
        }

        return $category;
    }

    /**
     * Получаем категорию
     * @param int $id
     * @return Category
     * @throws \Exception
     */
    public function find(int $id): Category
    {
        try {
            $category = $this->model->find($id);
        } catch (\Exception $exception){
            throw new \Exception('Ошибка при получении категории.');
        }
        return $category;
    }

    /**
     * @return Collection[Category]
     * @throws \Exception
     */
    public function all(): Collection
    {
        try {
            $category = $this->model->orderBy('id')->get();
        } catch (\Exception $exception){
            throw new \Exception('Ошибка при получении категорий.');
        }
        return $category;
    }
}
