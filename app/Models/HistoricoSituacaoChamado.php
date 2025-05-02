<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoricoSituacaoChamado extends Model
{
    use HasFactory;

    protected $table = 'historico_situacao_chamados'; // adequação do nome da table

    protected $fillable = ['chamado_id', 'situacao_id'];

    public function chamado(): BelongsTo {
        return $this->belongsTo(Chamado::class);
    }

    public function situacao(): BelongsTo {
        return $this->belongsTo(Situacao::class);
    }
}