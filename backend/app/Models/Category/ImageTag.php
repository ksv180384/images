<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property  int $id
 * @property  string $title
 * @param \Illuminate\Database\Eloquent\Relations\BelongsToMany images()
 */
class ImageTag extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'title',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function images(): \Illuminate\Database\Eloquent\Relations\BelongsToMany
    {
        return $this->belongsToMany(ImageItem::class, 'image_tags_to_images', 'image_tag_id', 'image_id');
    }
}
