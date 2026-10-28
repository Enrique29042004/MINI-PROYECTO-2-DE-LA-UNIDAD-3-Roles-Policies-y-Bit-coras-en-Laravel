<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Tabla pivote de la relacion muchos a muchos Producto <-> Categoria.
     */
    public function up(): void
    {
        Schema::create('categoria_producto', function (Blueprint $table) {
            $table->foreignId('producto_id')
                ->constrained('productos')
                ->cascadeOnDelete();

            $table->foreignId('categoria_id')
                ->constrained('categorias')
                ->cascadeOnDelete();

            // Un producto no puede repetir la misma categoria.
            $table->primary(['producto_id', 'categoria_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('categoria_producto');
    }
};
