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
        Schema::create('pedidos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_usuario');
            $table->unsignedBigInteger('id_grupo');
            $table->unsignedBigInteger('tracking');
            $table->string('etapa');
            $table->string('etiqueta');
            $table->string('barcode');
            $table->double('cantidad');
            $table->timestamps();

            $table->foreign('id_grupo', 'fk_pedidos_id_grupo')->on('grupo_pedidos')->references('id');
            $table->index('id_usuario', 'pedidos_idx_usuario');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pedidos');
    }
};
