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
        Schema::create('pistoleos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->string('etiqueta')->index('idx_etiqueta');
            $table->string('barcode');
            $table->double('quantity');
            $table->string('etapa', 3);
            $table->timestamps();

            $table->index(['id_usuario', 'etapa'], 'idx_usuario_etapa');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pistoleos');
    }
};
