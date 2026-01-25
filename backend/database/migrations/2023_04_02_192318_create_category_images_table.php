<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('category_id')->index();
            $table->string('title')->nullable()->default(null)->comment('название');
            $table->string('image_preview')->comment('путь к мини изображению');
            $table->string('image')->comment('путь к изображению');
            $table->string('description')->nullable()->default(null)->comment('описание изображения');
            $table->integer('width')->comment('высота изображения');
            $table->integer('height')->comment('ширина изображения');
            $table->timestamps();

            $table->foreign('category_id')
                ->references('id')
                ->on('categories')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('images', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
        });

        Schema::dropIfExists('images');
    }
};
