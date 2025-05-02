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
        Schema::create('chamados', function (Blueprint $table) {
            $table->id();
            $table->string('titulo');
            $table->foreignId('categoria_id')->constrained('categorias')->onDelete('cascade');
            $table->text('descricao');
            $table->date('prazo_solucao');
            $table->foreignId('situacao_id')->constrained('situacoes')->onDelete('cascade');
            $table->dateTime('data_criacao');
            $table->dateTime('data_solucao')->nullable();
            $table->timestamps();
        });
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('chamados');
    }
};
