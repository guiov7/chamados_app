<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Chamado;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class HomeController extends Controller {

    public function index(Request $request) {
        $hoje = Carbon::now();
    $mesReferencia = $request->input('mes', $hoje->format('Y-m'));
    $primeiroDiaDoMes = Carbon::parse($mesReferencia)->startOfMonth();
    $ultimoDiaDoMes = Carbon::parse($mesReferencia)->endOfMonth();

    $totalChamadosMesAtual = Chamado::whereBetween('data_criacao', [$primeiroDiaDoMes, $ultimoDiaDoMes])->count();

    $chamadosResolvidosMesAtual = Chamado::whereBetween('data_criacao', [$primeiroDiaDoMes, $ultimoDiaDoMes])
        ->whereHas('ultimaSituacao', function ($query) {
            $query->where('nome', 'Resolvido');
        })
        ->count();

    $chamadosResolvidosNoPrazoMesAtual = 0;
    if ($totalChamadosMesAtual > 0) {
        $chamadosResolvidosNoPrazoMesAtual = Chamado::whereBetween('data_criacao', [$primeiroDiaDoMes, $ultimoDiaDoMes])
            ->whereHas('ultimaSituacao', function ($query) {
                $query->where('nome', 'Resolvido');
            })
            ->where('data_solucao', '<=', \DB::raw('prazo_solucao'))
            ->count();
    }

    $percentualResolvidosNoPrazo = 0;
    if ($totalChamadosMesAtual > 0) {
        $percentualResolvidosNoPrazo = ($chamadosResolvidosNoPrazoMesAtual / $totalChamadosMesAtual) * 100;
    }

    $mensagem = null;
    if ($totalChamadosMesAtual === 0) {
        $mensagem = 'Nenhum registro encontrado para o mês de ' . Carbon::parse($mesReferencia)->format('F Y');
    }

    return view('home.index', compact('percentualResolvidosNoPrazo', 'totalChamadosMesAtual', 'chamadosResolvidosMesAtual', 'mensagem', 'mesReferencia'));
    }
}
