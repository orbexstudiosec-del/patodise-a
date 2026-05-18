<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('gallery_id')->nullable()->constrained()->nullOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('image_path');
            $table->string('thumbnail_path')->nullable();
            $table->decimal('price', 10, 2)->nullable(); // null => usa per_photo_price de la galería
            $table->unsignedInteger('stock')->default(99);
            $table->string('location')->nullable();
            $table->year('captured_year')->nullable();
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_published')->default(true);
            $table->timestamps();

            $table->index(['is_published', 'is_featured']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('photos');
    }
};
