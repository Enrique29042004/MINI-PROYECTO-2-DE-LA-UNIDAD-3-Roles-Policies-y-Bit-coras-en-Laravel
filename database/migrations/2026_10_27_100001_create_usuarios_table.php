<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('nombre', 60);
            $table->string('apellidos', 80);
            $table->string('correo', 120)->unique();
            $table->string('clave');
            $table->string('rol', 20)->default('cliente')->index(); // administrador | gerente | cliente
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('usuarios');
    }
};
