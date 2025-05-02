<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Categoria;
use App\Models\Situacao;
use Illuminate\Http\Request;

class ChamadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chamados = Chamado::all();
        return view('chamados.index', compact('chamados'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $situacoes = Situacao::all();

        return view('chamados.create', compact('categorias', 'situacoes'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validação dos dados do formulário
        $chamado = new Chamado();
        $chamado->titulo = $request->input('titulo');
        $chamado->categoria_id = $request->input('categoria_id');
        $chamado->descricao = $request->input('descricao');
        $chamado->situacao_id = Situacao::where('nome', 'Novo')->firstOrFail()->id; // Define a situação inicial como "Novo"
        $chamado->data_criacao = now(); // Define a data de criação com a data e hora atuais
        $chamado->data_solucao = null; // Inicialmente a data de solução é nula

        // Calcula o prazo de solução (3 dias corridos)
        $prazoSolucao = now()->addDays(3)->toDateString();
        $chamado->prazo_solucao = $prazoSolucao;

        // Salva o novo chamado no banco de dados
        try {
            $chamado->save();
            // Redireciona o usuário para a listagem de chamados com uma mensagem de sucesso
            return Redirect::route('chamados.index')->with('success', 'Chamado cadastrado com sucesso!');
        } catch (\Exception $e) {
            \Log::error('Erro ao salvar chamado: ' . $e->getMessage());
            return Redirect::back()->with('error', 'Ocorreu um erro ao salvar o chamado.');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Chamado $chamado)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chamado $chamado)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chamado $chamado)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chamado $chamado)
    {
        //
    }
}
