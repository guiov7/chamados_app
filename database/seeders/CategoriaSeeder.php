<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Categoria;

class CategoriaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Categoria::create(['nome' => 'Problema Técnico']);
        Categoria::create(['nome' => 'Sugestão de Melhoria']);
        Categoria::create(['nome' => 'Dúvida']);
        Categoria::create(['nome' => 'Solicitação de Serviço']);
    }
}
