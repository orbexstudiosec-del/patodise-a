<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // JSON: { 'digital' => ['enabled' => true, 'multiplier' => 1.0, 'label' => '...'], ... }
            // Si es null, la galería usa los formatos por defecto de CartService::FORMATS
            $table->json('formats')->nullable()->after('per_photo_price');
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn('formats');
        });
    }
};
