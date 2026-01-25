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
        Schema::create('image_tags', function (Blueprint $table) {
            $table->id();
            $table->string('title');
        });

        Schema::create('image_tags_to_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('image_id')->index();
            $table->unsignedBigInteger('image_tag_id')->index();

            $table->foreign('image_id')
                ->references('id')
                ->on('images')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            $table->foreign('image_tag_id')
                ->references('id')
                ->on('image_tags')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('image_tags_to_images', function (Blueprint $table) {
            $table->dropForeign(['image_id']);
            $table->dropForeign(['image_tag_id']);
        });

        Schema::dropIfExists('image_tags_to_images');
        Schema::dropIfExists('image_tags');
    }
};
