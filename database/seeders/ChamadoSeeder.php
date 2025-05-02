<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Chamado;
use App\Models\Categoria;
use App\Models\Situacao;
use Carbon\Carbon;
use Illuminate\Support\Arr;
// use Illuminate\Support\Facades\DB;

class ChamadoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obter todas as categorias e situações existentes
        $categorias = Categoria::all()->pluck('id')->toArray();
        $situacoes = Situacao::all()->pluck('id', 'nome')->toArray();

        if (empty($categorias) || empty($situacoes)) {
            $this->command->info('Por favor, execute as seeders de categorias e situações primeiro.');
            return;
        }

        $situacaoNovoId = $situacoes['Novo'] ?? null;
        $situacaoResolvidoId = $situacoes['Resolvido'] ?? null;
        $situacaoPendenteId = $situacoes['Pendente'] ?? null;
        $situacaoEmAndamentoId = $situacoes['Em Andamento'] ?? null;

        if ($situacaoNovoId === null || $situacaoResolvidoId === null) {
            $this->command->info('As situações "Novo" e "Resolvido" são obrigatórias. Verifique suas seeders de situações.');
            return;
        }

        $numChamados = rand(80, 120);

        for ($i = 0; $i < $numChamados; $i++) {
            $categoriaId = Arr::random($categorias);
            $situacaoInicialId = $situacaoNovoId;

            // Gerar datas variadas nos últimos meses
            $dataCriacao = Carbon::now()->subDays(rand(0, 90))->setHour(rand(8, 18))->setMinute(rand(0, 59))->setSecond(rand(0, 59));
            $prazoSolucao = $dataCriacao->copy()->addDays(3);

            $titulo = 'Chamado de Teste #' . ($i + 1);
            $descricao = 'Descrição detalhada do chamado de teste #' . ($i + 1) . '. Conteúdo aleatório para simulação.';

            $dataSolucao = null;
            $situacaoFinalId = $situacaoInicialId;

            // Simular resolução com maior probabilidade
            if (rand(1, 10) <= 7 && $situacaoResolvidoId !== null) {
                $situacaoFinalId = $situacaoResolvidoId;
                $dataSolucao = $dataCriacao->copy()->addHours(rand(1, 72))->setMinute(rand(0, 59))->setSecond(rand(0, 59));
                if ($dataSolucao->greaterThan($prazoSolucao->copy()->endOfDay())) {
                    // Simular resolução fora do prazo em alguns casos
                    if (rand(1, 4) == 1) {
                        $dataSolucao->addDays(rand(1, 2));
                    }
                }
            } else if (rand(1, 10) <= 9 && $situacaoPendenteId !== null) {
                $situacaoFinalId = $situacaoPendenteId;
            } else if ($situacaoEmAndamentoId !== null) {
                $situacaoFinalId = $situacaoEmAndamentoId;
            }

            Chamado::create([
                'titulo' => $titulo,
                'categoria_id' => $categoriaId,
                'descricao' => $descricao,
                'prazo_solucao' => $prazoSolucao,
                'situacao_id' => $situacaoFinalId,
                'data_criacao' => $dataCriacao,
                'data_solucao' => $dataSolucao,
            ]);
        }

        $this->command->info('Chamados de teste populados com sucesso!');
    }
}
