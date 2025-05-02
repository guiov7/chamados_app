<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use Illuminate\Http\Request;

class ChamadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
        $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descricao' => 'required|string',
            'prazo_solucao' => 'required|date',
            /*
            'situacao_id' automatico backend
            'data_criacao' automatico backend */
            'data_solucao' => 'nullable|date',
        ]);

        // Cria novo Chamado com os dados do form
        $chamado = new Chamado();
        $chamado->titulo = $request->input('titulo');
        $chamado->categoria_id = $request->input('categoria_id');
        $chamado->descricao = $request->input('descricao');
        $chamado->prazo_solucao = $request->input('prazo_solucao');
        $chamado->situacao_id = Situacao::where('nome', 'Novo')->firstOrFail()->id; //situação inicial "Novo"
        $chamado->data_criacao = now(); // data de criação = datatimeatual
        $chamado->data_solucao = null; // data inicial nula

        // Salva novo no database
        $chamado->save();

        // Redireciona para listagem
        return Redirect::route('chamados.index')->with('success', 'Chamado cadastrado com sucesso!');
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
