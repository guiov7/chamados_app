<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Situacao extends Model
{
    use HasFactory;

    protected $fillable = [
        'nome',
    ];

    public function chamados(): HasMany
    {
        return $this->hasMany(Chamado::class);
    }
}
