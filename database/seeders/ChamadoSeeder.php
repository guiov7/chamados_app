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

        $numChamados = rand(100, 150);

        for ($i = 0; $i < $numChamados; $i++) {
            $categoriaId = Arr::random($categorias);
            $situacaoInicialId = $situacaoNovoId;

            $dataCriacao = Carbon::now()->subDays(rand(0, 120))->setHour(rand(8, 18))->setMinute(rand(0, 59))->setSecond(rand(0, 59));
            $prazoSolucao = $dataCriacao->copy()->addDays(3)->endOfDay();

            $titulo = 'Chamado de Teste #' . ($i + 1);
            $descricao = 'Descrição detalhada do chamado de teste #' . ($i + 1) . '. Conteúdo aleatório para simulação.';
            $dataResolvido = null;
            $situacaoFinalId = $situacaoInicialId;

            if (rand(1, 10) <= 8 && $situacaoResolvidoId !== null) {
                $situacaoFinalId = $situacaoResolvidoId;
                $dataResolvido = $dataCriacao->copy()->addHours(rand(1, 96))->setMinute(rand(0, 59))->setSecond(rand(0, 59));
                if (rand(1, 3) == 1) {
                    // Simular chamados resolvidos fora do prazo
                    $dataResolvido->addDays(rand(1, 5));
                } else {
                    // Garantir que a data de resolução não seja antes da criação
                    if ($dataResolvido->lessThan($dataCriacao)) {
                        $dataResolvido = $dataCriacao->copy()->addHours(1);
                    }
                    // Garantir que a data de resolução não seja antes do prazo em chamados resolvidos no prazo
                    if ($dataResolvido->greaterThan($prazoSolucao)) {
                        if (rand(1, 2) == 1) {
                            $dataResolvido = $prazoSolucao->copy()->subHours(rand(0, 12));
                        }
                    }
                }
            } else if ($situacaoPendenteId !== null && rand(1, 10) <= 2) {
                $situacaoFinalId = $situacaoPendenteId;
            } else if ($situacaoEmAndamentoId !== null && rand(1, 10) <= 2) {
                $situacaoFinalId = $situacaoEmAndamentoId;
            }

            Chamado::create([
                'titulo' => $titulo,
                'categoria_id' => $categoriaId,
                'descricao' => $descricao,
                'prazo_solucao' => $prazoSolucao->toDateString(),
                'situacao_id' => $situacaoFinalId,
                'data_criacao' => $dataCriacao,
                'data_resolvido' => $dataResolvido,
            ]);
        }

        $this->command->info('Chamados de teste populados com sucesso (com dados de SLA)!');
    }
}
