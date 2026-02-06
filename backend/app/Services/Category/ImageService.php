<?php
namespace App\Services\Category;

use App\Http\Filters\CategoryImagesFilter;
use App\Http\Filters\ImagesFilter;
use App\Models\Category\ImageItem;
use App\Services\FaceDetected\FaceDetectedService;
use App\Services\Service;
use App\Services\UploadFile;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\Facades\Image;

class ImageService extends Service
{
    public function __construct()
    {
        parent::__construct();
        $this->model = new ImageItem();
    }

    /**
     * Получаем изображение
     * @param int $id
     * @return ImageItem
     * @throws \Exception
     */
    public function find(int $id): ImageItem
    {
        try {
            $image = $this->model->find($id);
        } catch (\Exception $exception){
            throw new \Exception('Изображения не существует.');
        }
        return $image;
    }

    /**
     * Получаем все изображения категории
     * @param int $categoryId
     * @return mixed
     * @throws \Exception
     */
    public function getByCategoryId(int $categoryId): Collection
    {

        $imagesFilter = new CategoryImagesFilter(request());

        $images = $this->model->filter($imagesFilter)->where('category_id', $categoryId)->get();

        return $images;
    }

    /**
     * Добавляем новое изображение
     * @param array $imageData [category_id, files]
     * @return Image
     * @throws \Exception
     */
    public function create(array $imageData): ImageItem
    {
        $files = $imageData['files'];
        $file = $files[0];
        $categoryId = $imageData['category_id'];
        $path = 'categories/' . $categoryId . '/images';
        $pathPreview = 'categories/' . $categoryId . '/images_preview';

        $imagePath = (new UploadFile())->upload($file, $path);
        $imagePreviewPath = (new UploadFile())->upload($file, $pathPreview, 320, 320);

        try {
            $image = DB::transaction(function() use (
                $categoryId,
                $file,
                $imagePath,
                $imagePreviewPath
            ) {

                $width = Image::make($file)->width();
                $height = Image::make($file)->height();

                $image = $this->model->create([
                    'category_id' => $categoryId,
                    'image' => $imagePath,
                    'image_preview' => $imagePreviewPath,
                    'width' => $width,
                    'height' => $height,
                ]);

                (new FaceDetectedService($image))->addImage();

                return $image;
            }, 3);
        } catch (\Exception $exception) {
            Storage::disk('public')->delete($imagePath);
            Storage::disk('public')->delete($imagePreviewPath);
            throw new \Exception('Ошибка при добавлении изображения.');
        }

        return $image;
    }

    /**
     * Удаляем изображение
     * @param int $idImage
     * @return bool
     */
    public function delete(int $idImage): mixed
    {
        try {
            $result = DB::transaction(function() use ($idImage) {
                $image = $this->model->where('id', $idImage)->first();
                $imageFull = $image->image;
                $imagePreview = $image->image_preview;

                // Удаляем данные изображения в другом сервисе
                $result = (new FaceDetectedService($image))->deleteImage();
                $image->delete();

                Storage::disk('public')->delete($imageFull);
                Storage::disk('public')->delete($imagePreview);

                return $result->body();

            }, 3);
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка удаления изображения.');
        }

        return $result;
    }

    /**
     * Получем изображения по пути к изображению
     * @param array $namesList
     * @return Collection
     */
    public function getImagesIn(array $namesList): Collection
    {
        try {
            $images = $this->model->whereIn('image', $namesList)->get();
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка получения изображения.');
        }
        return $images;
    }

    /**
     * Добавляем теги к картинке
     * @param int $imageId
     * @param array $tagsList
     * @return ImageItem
     */
    public function attachTags(int $imageId, array $tagsList): ImageItem
    {
        try {
            $image = $this->model->find($imageId);
            $image->tags()->sync($tagsList);

            $image->load('tags');
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка при добавления тегов.');
        }

        return $image;
    }

    /**
     * Получаем изображения по тегам
     * @param int $categoryId
     * @param array $tagsIds
     * @return Collection
     */
    public function getImagesByCategoryIdAndTagsIds(
        int $categoryId,
        array $tagsIds
    ): Collection
    {
        try {
            $images = $this->model->whereHas('tags', function ($q) use ($tagsIds) {
                $q->whereIn('image_tags.id', $tagsIds);
            })
                ->where('images.category_id', '=', $categoryId)
                ->get();
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка при получении изображений.');
        }

        return $images;
    }

    /**
     * Изменяем дату создания изображения (если фото, то дата когда был сделан снимок)
     * @param array $data
     * @return ImageItem
     */
    public function updateDateCreateImage(array $data): ImageItem
    {
        try {
            $image = $this->model->find($data['image_id']);

            $image->update([
                'image_created_at' => $data['date'],
            ]);
            //$image->refresh();
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка при сохранении даты создания изображения.');
        }

        return $image;
    }

    /**
     * Получаем изображения с учетом фильтра
     * @param int $categoryId
     * @param ImagesFilter $filterParams
     * @return Collection
     */
    public function getByCategoryFilter(
        int $categoryId,
        ImagesFilter $filterParams
    ): Collection
    {
        try {
            $images = $this->model->filter($filterParams)->where('category_id', $categoryId)->get();
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка при получение изображений.');
        }

        return $images;
    }

    /**
     * Получаем похожие изображения
     * @param int $imageId
     * @return Collection
     * @throws \Exception
     */
    public function similar(int $imageId): Collection
    {
        $image = $this->model->find($imageId);
        $img = 'data:image/jpg;base64,' . base64_encode(file_get_contents(Storage::disk('public')->path($image->image)));

        $result = (new FaceDetectedService())->searchByPhoto($image->category_id, $img);
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

        return $images;
    }
}

