<?php

namespace Database\Seeders;

use App\Models\Situacao;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SituacaoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (!Situacao::where('nome', 'Novo')->exists()) {
            Situacao::create(['nome' => 'Novo']);
        }
        Situacao::create(['nome' => 'Em Andamento']);
        Situacao::create(['nome' => 'Resolvido']);
    }
}
