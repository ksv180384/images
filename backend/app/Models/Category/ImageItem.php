<?php

namespace App\Models\Category;

use App\Http\Filters\Filterable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * @property int $id
 * @property int $category_id
 * @property string $title
 * @property string $image_preview
 * @property string $image
 * @property string $description
 * @property string $width
 * @property string $height
 * @property string $path_full
 * @property string $path_preview_full
 * @property string $path_face
 * @property bool $is_features
 * @param \Illuminate\Database\Eloquent\Relations\BelongsToMany tags()
 */
class ImageItem extends Model
{
    use HasFactory;
    use Filterable;

    protected $table = 'images';

    protected $fillable = [
        'category_id',
        'title',
        'image_preview',
        'image',
        'description',
        'image_created_at',
        'width',
        'height',
    ];

    protected $casts = [
        'image_created_at' => 'datetime',
    ];

    protected $appends = ['path_full', 'path_preview_full', 'path_face', 'is_features'];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function tags(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ImageTag::class, 'image_tags_to_images', 'image_id', 'image_tag_id');
    }

    /**
     * Полный путь до превью изображения
     * @return string
     */
    public function getPathPreviewFullAttribute(): string
    {
        return Storage::disk('public')->url($this->image_preview);
    }

    /**
     * Полный путь до изображения
     * @return string
     */
    public function getPathFullAttribute(): string
    {
        return Storage::disk('public')->url($this->image);
    }

    /**
     * Путь до изображения лица
     * @return string
     */
    public function getPathFaceAttribute(): string
    {
        $imagePath = Str::replace('images', 'faces', $this->image);
        if(Storage::disk('public')->exists($imagePath)){
            return Storage::disk('public')->url($imagePath);
        }
        return '';
    }

    /**
     * Есть ли файл с фрагметами для поиска по изображениям
     * @return bool
     */
    public function getIsFeaturesAttribute(): bool
    {
        $fraturesPath = Str::replace('images', 'features', $this->image);
        $arStrPath = explode('.', $fraturesPath);
        $arStrPath[count($arStrPath) - 1] = 'npy';
        $fraturesPath = implode('.', $arStrPath);
        return !!Storage::disk('public')->exists($fraturesPath);
    }
}
