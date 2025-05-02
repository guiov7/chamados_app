<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Categoria;
use App\Models\Situacao;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

class ChamadoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $chamados = Chamado::all();
        $situacoes = Situacao::all();
        return view('chamados.index', compact('chamados', 'situacoes'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $categorias = Categoria::all();
        $situacaoNovo = Situacao::where('nome', 'Novo')->first();
        // dd($situacaoNovo);  # debug

        return view('chamados.create', compact('categorias', 'situacaoNovo'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // \Log::info('Dados recebidos (JSON):', $request->all());
        $data = $request->all();

        $validator = Validator::make($data, [
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descricao' => 'required|string',
            'prazo_solucao' => 'required|date',
            'situacao_id' => 'required|exists:situacoes,id',
            'data_criacao' => 'required|date',
            'data_solucao' => 'nullable|date',
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => 'Erro de validação.', 'errors' => $validator->errors()], 422);
        }

        $chamado = new Chamado();
        $chamado->titulo = $data['titulo'];
        $chamado->categoria_id = $data['categoria_id'];
        $chamado->descricao = $data['descricao'];
        $chamado->prazo_solucao = $data['prazo_solucao'];
        $chamado->situacao_id = $data['situacao_id'];
        $chamado->data_criacao = $data['data_criacao'];
        $chamado->data_solucao = $data['data_solucao'] ?? null;

        if ($chamado->save()) {
            return response()->json(['success' => true, 'message' => 'Chamado cadastrado com sucesso!'], 200, ['Content-Type' => 'application/json']);
        } else {
            return response()->json(['success' => false, 'message' => 'Erro ao salvar o chamado.']);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Chamado $chamado)
    {
        return view('chamados.show', compact('chamado'));
    }

    /**
     * atualizar situacao do chamado.
     */
    public function atualizarSituacao(Request $request, Chamado $chamado)
    {
        $request->validate([
            'situacao_id' => 'required|exists:situacoes,id|in:' . Situacao::whereIn('nome', ['Pendente', 'Resolvido'])->pluck('id')->implode(','),
        ]);

        $chamado->situacao_id = $request->input('situacao_id');

        if ($chamado->situacao->nome === 'Resolvido' && $chamado->data_solucao === null) {
            $chamado->data_solucao = now();
        }

        if ($chamado->save()) {
            return response()->json(['success' => true, 'message' => 'Situação do chamado atualizada com sucesso!']);
        } else {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar a situação do chamado.']);
        }
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
        $chamado->delete();

        return Redirect::route('chamados.index')->with('success', 'Chamado excluído com sucesso!');
    }
}
