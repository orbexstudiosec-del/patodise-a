<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('page_views', function (Blueprint $table) {
            $table->id();
            $table->string('session_id', 64)->index();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('path', 500);
            $table->string('referer', 500)->nullable();
            $table->string('ip', 64)->nullable();
            $table->string('user_agent', 500)->nullable();
            $table->timestamp('created_at')->index();

            $table->index(['path', 'created_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('page_views');
    }
};
