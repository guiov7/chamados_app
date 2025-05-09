<?php

namespace App\Http\Controllers;

use App\Models\Chamado;
use App\Models\Categoria;
use App\Models\Situacao;
use App\Models\HistoricoSituacaoChamado;
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
        $categorias = Categoria::all();
        return view('chamados.index', compact('chamados', 'situacoes','categorias'));
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

        $novaSituacaoId = $request->input('situacao_id');

        // Salva no histórico
        $historico = new HistoricoSituacaoChamado();
        $historico->chamado_id = $chamado->id;
        $historico->situacao_id = $novaSituacaoId;
        $historico->save();

        // Atualiza a situação atual do chamado
        $chamado->situacao_id = $novaSituacaoId;

        if ($chamado->situacao->nome === 'Resolvido' && $chamado->data_solucao === null) {
            $chamado->data_solucao = now();
        }

        if ($chamado->save()) {
            return response()->json(['success' => true, 'message' => 'Situação atualizada com sucesso!', 'nova_situacao' => $chamado->situacao->nome]);
        } else {
            return response()->json(['success' => false, 'message' => 'Erro ao atualizar a situação do chamado.']);
        }
    }

    public function salvarHistoricoSituacao(Request $request, Chamado $chamado) {
        $request->validate([
            'situacao_id' => 'required|exists:situacoes,id|in:' . Situacao::whereIn('nome', ['Resolvido', 'Em Andamento'])->pluck('id')->implode(','),
        ]);

        $novaSituacaoId = $request->input('situacao_id');

        $historico = new HistoricoSituacaoChamado();
        $historico->chamado_id = $chamado->id;
        $historico->situacao_id = $novaSituacaoId;
        $historico->save();

        $chamado->situacao_id = $novaSituacaoId;

        if ($chamado->situacao->nome === 'Resolvido' && $chamado->data_resolvido === null) {
            $chamado->data_resolvido = now();
        }

        if ($chamado->save()) {
            return response()->json(['success' => true, 'message' => 'Histórico de situação atualizado!', 'nova_situacao' => $chamado->situacao->nome]);
        } else {
            return response()->json(['success' => false, 'message' => 'Erro ao salvar o histórico de situação.']);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Chamado $chamado) {
        $categorias = Categoria::all();
        return view('chamados.edit', compact('chamado', 'categorias'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Chamado $chamado) {
        $request->validate([
            'titulo' => 'required|string|max:255',
            'categoria_id' => 'required|exists:categorias,id',
            'descricao' => 'required|string',
            'prazo_solucao' => 'required|date',
            'situacao_id' => 'required|exists:situacoes,id',
            'data_criacao' => 'required|date',
            'data_solucao' => 'nullable|date',
        ]);

        $chamado->titulo = $request->input('titulo');
        $chamado->categoria_id = $request->input('categoria_id');
        $chamado->descricao = $request->input('descricao');
        $chamado->prazo_solucao = $request->input('prazo_solucao');
        $chamado->situacao_id = $request->input('situacao_id');
        $chamado->data_criacao = $request->input('data_criacao');
        $chamado->data_solucao = $request->input('data_solucao');

        if ($chamado->save()) {
            return redirect()->route('chamados.index')->with('success', 'Chamado atualizado com sucesso!');
        } else {
            return back()->with('error', 'Erro ao atualizar o chamado.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Chamado $chamado)
    {
        $chamado->delete();

        return Redirect::route('chamados.index')->with('success', 'Chamado excluído com sucesso!');
    }

    /**
     * Filter a specific resource from storage.
     */
    public function filtrarChamados(Request $request) {
        $query = Chamado::query()->with('categoria', 'ultimaSituacao');

        if ($request->filled('titulo')) {
            $query->where('titulo', 'like', '%' . $request->input('titulo') . '%');
        }

        if ($request->filled('categoria_id')) {
            $query->where('categoria_id', $request->input('categoria_id'));
        }

        if ($request->filled('data_criacao')) {
            $query->whereDate('data_criacao', $request->input('data_criacao'));
        }

        if ($request->filled('prazo_solucao')) {
            $query->whereDate('prazo_solucao', $request->input('prazo_solucao'));
        }

        if ($request->filled('concluido')) {
            if ($request->input('concluido') === 'sim') {
                $query->whereNotNull('data_resolvido');
            } elseif ($request->input('concluido') === 'nao') {
                $query->whereNull('data_resolvido');
            }
        }

        if ($request->filled('situacao_id')) {
            $query->where('situacao_id', $request->input('situacao_id'));
        }

        $chamadosFiltrados = $query->get();

        return response()->json($chamadosFiltrados);
    }
}
