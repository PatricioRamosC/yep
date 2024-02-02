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
        Schema::create('grupo_sku', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('id_grupo');
            $table->string('sku');
            $table->double('cantidad');
            $table->timestamps();

            $table->foreign('id_grupo')->on('grupo_pedidos')->references('id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('grupo_sku');
    }
};
