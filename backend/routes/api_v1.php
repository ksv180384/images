<?php

use Illuminate\Support\Facades\Route;


Route::get('/', [\App\Http\Controllers\Api\V1\IndexController::class, 'index']);

Route::group(['middleware' => 'auth'], function(){
    // Категории
    Route::get('categories', [\App\Http\Controllers\Api\V1\Admin\Category\CategoryController::class, 'index']);
    Route::get('category/edit/{id}', [\App\Http\Controllers\Api\V1\Admin\Category\CategoryController::class, 'edit']);

    // Теги
    Route::get('/tags', [\App\Http\Controllers\Api\V1\Admin\Category\ImageTagController::class, 'tagsPage']);
});

// Админка
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function(){
    // Категории
    Route::get('categories', [\App\Http\Controllers\Api\V1\App\Category\CategoryController::class, 'index']);
    Route::get('category/edit/{id}', [\App\Http\Controllers\Api\V1\App\Category\CategoryController::class, 'show']);

    // Теги
    Route::get('/tags', [\App\Http\Controllers\Api\V1\Admin\Category\ImageTagController::class, 'tagsPage']);
});

// Админка
Route::group(['prefix' => 'admin', 'middleware' => 'auth'], function(){

    // Категории
    Route::post('category/store', [\App\Http\Controllers\Api\v1\Admin\Category\CategoryController::class, 'store']);
    Route::post('category/update', [\App\Http\Controllers\Api\v1\Admin\Category\CategoryController::class, 'update']);

    // Работа с изображением через API python
    Route::get('/detected/get-face', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'getFace']);
    Route::get('/detected/detected-faces', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'detectedFacesByImage']);
    Route::post('/detected/face-crop', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'faceCrop']);
    Route::post('/detected/search-by-photo', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'searchByPhoto']);

    // Теги
    Route::post('/tags/attach-images', [\App\Http\Controllers\Api\v1\Admin\Category\ImageTagController::class, 'attachImages']);
    Route::post('/tags/attach-images-by-tags', [\App\Http\Controllers\Api\v1\Admin\Category\ImageTagController::class, 'imagesByTagIds']);
    Route::post('/tag/create', [\App\Http\Controllers\Api\v1\Admin\Category\ImageTagController::class, 'store']);
    Route::post('/tag/delete/{id}', [\App\Http\Controllers\Api\v1\Admin\Category\ImageTagController::class, 'delete']);

    // Изображение
    Route::get('category/{categoryId}/images/', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'getByCategoryId']);
    Route::post('image/create', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'store']);
    Route::post('image/delete', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'delete']);
    Route::post('image/save-create-date', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'saveCreateDate']);
    Route::get('image/get-info/{imageId}', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'getImageMetadata']);
    Route::post('image/save-format/{imageId}', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'saveFormat']);
    Route::post('image/filter', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'filter']);
    Route::get('image/similar/{id}', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'similar']);
    Route::get('image/{imageId}', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'getById']);
    Route::get('category/{categoryId}/image/{imageId}', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'getById']);

    // Теги картинок
    Route::post('/image/tags', [\App\Http\Controllers\Api\v1\Admin\Category\ImageTagController::class, 'all']);
    Route::get('/image/{image_id}/tags', [\App\Http\Controllers\Api\v1\Admin\Category\ImageTagController::class, 'getByImageId']);


    Route::post('category/image/tag/attach-to-image', [\App\Http\Controllers\Api\v1\Admin\Category\ImageController::class, 'attachToImage']);
});

// Авторизация
require __DIR__.'/auth.php';

