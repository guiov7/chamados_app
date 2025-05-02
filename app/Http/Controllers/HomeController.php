<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Chamado;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        $hoje = Carbon::now();
        $primeiroDiaDoMes = $hoje->copy()->startOfMonth();
        $ultimoDiaDoMes = $hoje->copy()->endOfMonth();

        $totalChamadosMesAtual = Chamado::whereBetween('data_criacao', [$primeiroDiaDoMes, $ultimoDiaDoMes])->count();

        $chamadosResolvidosNoPrazoMesAtual = Chamado::whereBetween('data_criacao', [$primeiroDiaDoMes, $ultimoDiaDoMes])
            ->whereHas('ultimaSituacao', function ($query) {
                $query->where('nome', 'Resolvido');
            })
            ->where('data_solucao', '<=', DB::raw('prazo_solucao'))
            ->count();

        $percentualResolvidosNoPrazo = 0;
        if ($totalChamadosMesAtual > 0) {
            $percentualResolvidosNoPrazo = ($chamadosResolvidosNoPrazoMesAtual / $totalChamadosMesAtual) * 100;
        }

        return view('home.index', compact('percentualResolvidosNoPrazo'));
    }
}
