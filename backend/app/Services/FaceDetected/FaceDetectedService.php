<?php

namespace App\Services\FaceDetected;

use App\Models\Category\ImageItem;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Http;

/**
 * Класс для работы с сервисом который позволяет работать с изображениями
 */
class FaceDetectedService
{
    private string $servicePath;
    private ImageItem|null $image;

    public function __construct(ImageItem $image = null)
    {
        $this->servicePath = env('SERVICE_PYTHON_URL');
        $this->image = $image;
    }

    /**
     * Формируем данные изображения
     * @return \Illuminate\Http\Client\Response
     */
    public function addImage(): \Illuminate\Http\Client\Response
    {
        $imageFile = $this->getFileName($this->image->image);

        $result = Http::connectTimeout(600)
            ->post(
            $this->servicePath . 'detected-face/add-image/',
            [
                'category_id' => $this->image->category_id,
                'file_name' => $imageFile,
            ]
        );

        return $result;
    }

    /**
     * Удаляем данные изображения
     * @return \Illuminate\Http\Client\Response
     */
    public function deleteImage(): \Illuminate\Http\Client\Response
    {
        $imageFile = $this->getFileName($this->image->image);

        $result = Http::post(
            $this->servicePath . 'detected-face/delete-photo/',
            [
                'image' => $imageFile,
                'category_id' => $this->image->category_id,
            ]
        );

        return $result;
    }

    /**
     * Получаем лицо с изображения
     * @return \Illuminate\Http\Client\Response
     */
    public function getFace(): \Illuminate\Http\Client\Response
    {
        try {
            $imageFile = $this->getFileName($this->image->image);

            $result = Http::get(
                $this->servicePath . 'detected-face/face?' . 'file_name=' . $imageFile . '&category_id=' . $this->image->category_id,
            );
        } catch (\Exception $exception){
            throw new \Exception('Ошибка при получении лица с изображения.');
        }

        return $result;
    }

    /**
     * Определяем лица на изображении
     * @return \Illuminate\Http\Client\Response
     */
    public function detectedFace(): \Illuminate\Http\Client\Response
    {
        try {
            $imageFile = $this->getFileName($this->image->image);

            $result = Http::connectTimeout(600)
                ->get(
                    $this->servicePath . 'detected-face/detected?' . 'file_name=' . $imageFile . '&category_id=' . $this->image->category_id,
                );
        } catch (\Exception $exception){
            throw new \Exception('Ошибка определения лиц на изображении.');
        }

        return $result;
    }

    /**
     * Вырезаем и сохраняем лицо и изображения
     * @param Image $image
     * @param array $position
     * @return \Illuminate\Http\Client\Response
     */
    public function saveDetectedFace(array $position): \Illuminate\Http\Client\Response
    {
        try {
            $imageFile = $this->getFileName($this->image->image);

            $result = Http::post(
                $this->servicePath . 'detected-face/face-crop',
                [
                    'file_name' => $imageFile,
                    'category_id' => $this->image->category_id,
                    'position' => $position,
                ]
            );
        } catch (\Exception $exception) {
            throw new \Exception('Ошибка при вырезании лица из изображения.');
        }

        return $result;
    }

    /**
     * Вырезаем и сохраяем лица из всех изображений в категории
     * Одно изображение - одно дицо
     * @param int $categoryId
     * @return void
     */
    public function allDetectedFacesByCategory(int $categoryId)
    {

    }

    /**
     * Поиск похожих изображений в категории
     * @param int $categoryId
     * @param string $imgBase64
     * @return void
     */
    public function searchByPhoto(
        int $categoryId,
        string $imgBase64
    ): \Illuminate\Http\Client\Response
    {
        //try {
            $result = Http::post(
                $this->servicePath . 'detected-face/search-by-photo',
                [
                    'category_id' => $categoryId,
                    'img_base64' => $imgBase64,
                ]
            );
            $result->body();
//        } catch (\Exception $exception){
//            throw new \Exception('Ошибка при поиске похожих изображений.');
//        }

        return $result;
    }

    /**
     * Получаем метаданные изображения
     * @return string
     * @throws \Exception
     */
    public function getImageMetadata(): string
    {
        try {
            $imageFile = $this->getFileName($this->image->image);

            $result = Http::post(
                $this->servicePath . 'detected-face/get-image-info',
                [
                    'category_id' => $this->image->category_id,
                    'file_name' => $imageFile,
                ]
            );
            echo $result->body();
//            dd($result->body());
        } catch (\Exception $exception){
            throw new \Exception('Ошибка при получании информации об изображении.');
        }

        return $result;
    }

    /**
     * Получаем название файла из пути к файлу
     * @param string $filePath
     * @return string
     */
    private function getFileName(string $filePath): string
    {
        return pathinfo($filePath)['basename'];
    }
}
