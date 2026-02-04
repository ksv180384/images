<?php

namespace App\Services\Tag;

use App\Models\Category\ImageTag;

class ImageTagService
{

    public function all()
    {
        $tags = ImageTag::query()->get(['id', 'title']);

        return $tags;
    }
}
