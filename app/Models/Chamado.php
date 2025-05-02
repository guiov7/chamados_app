<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Chamado extends Model
{
    use HasFactory;

    protected $fillable = [
        'titulo',
        'categoria_id',
        'descricao',
        'prazo_solucao',
        'situacao_id',
        'data_criacao',
        'data_solucao',
    ];

    public function categoria(): BelongsTo
    {
        return $this->belongsTo(Categoria::class);
    }

    public function situacao(): BelongsTo
    {
        return $this->belongsTo(Situacao::class);
    }

    public function historicoSituacoes() {
        return $this->hasMany(HistoricoSituacaoChamado::class);
    }

    public function ultimaSituacao() {
        return $this->belongsTo(Situacao::class, 'situacao_id');
    }
}
