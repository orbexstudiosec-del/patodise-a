<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            // public = aparece en /galerias y se accede por /galeria/{slug}
            // unlisted = solo accesible por /g/{token} (no aparece en listados públicos)
            // private = /g/{token} + contraseña
            $table->string('visibility', 20)->default('public')->after('is_featured');
            $table->string('share_token', 64)->nullable()->unique()->after('visibility');
            $table->string('share_password')->nullable()->after('share_token');
            $table->string('client_name', 120)->nullable()->after('share_password'); // p/ admin: a quién va dirigida
        });
    }

    public function down(): void
    {
        Schema::table('galleries', function (Blueprint $table) {
            $table->dropColumn(['visibility', 'share_token', 'share_password', 'client_name']);
        });
    }
};
