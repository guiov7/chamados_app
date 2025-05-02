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
        Schema::create('historico_situacao_chamado', function (Blueprint $table) { /* tabela intermediaria de situacao e chamado */
            $table->id();
            $table->foreignId('chamado_id')->constrained('chamados')->onDelete('cascade');
            $table->foreignId('situacao_id')->constrained('situacoes')->onDelete('restrict');
            $table->timestamp('data_alteracao')->default(now());
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('historico_situacao_chamado');
    }
};
