<?php

namespace App\Models\Category;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $title
 * @property string $url
 * @property string $description
 */
class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'url',
        'description'
    ];
}
