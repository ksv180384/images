<?php
namespace App\Services\Category;

use App\Models\Category\ImageTag;
use App\Services\Service;
use Illuminate\Database\Eloquent\Collection;


class ImageTagService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ImageTag();
    }

    /**
     * Добавляем новый тег
     * @param array $tagData
     * @return ImageTag
     */
    public function create(array $tagData): ImageTag
    {
        return $this->model->create([
            'title' => $tagData['title'],
        ]);
    }

    /**
     * Получаем все теги
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function all()
    {
        try {
            return $this->model->orderBy('title')->get();
        } catch (\Exception $exception){
            throw new \Exception('Ошибка при получении тегов.');
        }
    }

    /**
     * Получаем теги прикрепленные к избражению
     * @param int $imageId
     * @return Collection
     */
    public function getByImageId(int $imageId): Collection
    {
        try {
            $tags = $this->model
                ->join('image_tags_to_images', 'image_tags.id', '=', 'image_tags_to_images.image_tag_id')
                ->where('image_tags_to_images.image_id', $imageId)
                ->get()
                ->map(function($item){
                    $tag = new ImageTag();
                    $tag->id = $item->image_tag_id;
                    $tag->title = $item->title;
                    return $tag;
                });
        } catch (\Exception $exception){
            throw new \Exception('Ошибка при получении тегов.');
        }

        return $tags;
    }

    /**
     * Прикрепляем изобоажения к тегам
     * @param array $tagsIds
     * @param array $imagesIds
     * @return bool
     */
    public function attachImages(array $tagsIds, array $imagesIds): bool
    {
        try {
            $tags = ImageTag::query()->whereIn('id', $tagsIds)->get();
            foreach ($tags as $tag){
                $tag->images()->sync($imagesIds);
            }
        } catch (\Exception $exception){
            throw new \Exception('Ошибка при прикреплении изображений к тегам.');
        }

        return true;
    }

    /**
     * Удаляем тег
     * @param int $id
     * @return void
     */
    public function delete(int $id): void
    {
        try {
            $this->model->find($id)->delete();
        } catch (\Exception $exception){
            throw new \Exception('Ошибка при удалении тега.');
        }
    }
}
