<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('item_type')->default('photo'); // photo, gallery
            $table->foreignId('photo_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('gallery_id')->nullable()->constrained()->nullOnDelete();
            $table->string('item_title');
            $table->string('format')->default('digital'); // digital, print_a4, print_a3, canvas, full_gallery
            $table->decimal('unit_price', 10, 2);
            $table->unsignedInteger('quantity');
            $table->decimal('line_total', 10, 2);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('order_items');
    }
};
