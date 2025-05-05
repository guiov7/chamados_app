<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Chamados</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{asset('/css/global.css')}}">
    <link rel="stylesheet" href="{{asset('/css/chamados-list.css')}}">
</head>
<body>
    <h1>Listagem de Chamados</h1>
        <div class="action-bar">
            <a id="btnNovo" href="{{ route('chamados.create') }}" class="action-button">Novo Chamado</a>
            <a id="btnHome" href="{{ route('home.index') }}" class="action-button">Homepage / SLA Rating</a>
        </div>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if ($chamados->isEmpty())
            <p class="empty-message">Nenhum chamado cadastrado.</p>
        @else
            <div class="table-container">
            <div class="filter-bar">
        <div class="filter-item">
            <label for="filter-titulo">Título:</label>
            <input type="text" id="filter-titulo" class="filter-input" placeholder="Filtrar por título">
        </div>
        <!-- <div class="filter-item">
            <label for="filter-categoria">Categoria:</label>
            <select id="filter-categoria" class="filter-select">
                <option value="">Todas</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                @endforeach
            </select>
        </div>
        <div class="filter-item">
            <label for="filter-data-criacao">Data de Criação:</label>
            <input type="date" id="filter-data-criacao" class="filter-input">
        </div>
        <div class="filter-item">
            <label for="filter-prazo-solucao">Prazo Solução:</label>
            <input type="date" id="filter-prazo-solucao" class="filter-input">
        </div>
        <div class="filter-item">
            <label for="filter-concluido">Concluído:</label>
            <select id="filter-concluido" class="filter-select">
                <option value="">Todos</option>
                <option value="sim">Sim</option>
                <option value="nao">Não</option>
            </select>
        </div>
        <div class="filter-item">
            <label for="filter-situacao">Situação:</label>
            <select id="filter-situacao" class="filter-select">
                <option value="">Todas</option>
                @foreach ($situacoes as $situacaoOpcao)
                    <option value="{{ $situacaoOpcao->id }}">{{ $situacaoOpcao->nome }}</option>
                @endforeach
            </select>
        </div>
        <button class="action-button" onclick="filtrarChamados()">Filtrar</button>
        <button class="action-button" onclick="limparFiltros()">Limpar Filtros</button>
    </div> -->
            <table>
                <thead>
                    <tr>
                        <th>Título</th>
                        <th>Categoria</th>
                        <th>Data de Criação</th>
                        <th>Prazo Solução</th>
                        <th>Concluído</th>
                        <th>Situação</th>
                        <th>Ações</th>
                    </tr>
                </thead>
                <tbody id="chamados-table-body">
                    @foreach ($chamados as $chamado)
                    <tr class="clickable-row" onclick="window.location='{{ route('chamados.show', $chamado->id) }}';" style="cursor: pointer;">
                        <td>{{ $chamado->titulo }}</td>
                        <td>{{ $chamado->categoria->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($chamado->data_criacao)->format('d/m/Y H:i:s') }}</td>
                        <td>{{ \Carbon\Carbon::parse($chamado->prazo_solucao)->format('d/m/Y') }}</td>
                        <td class="{{ $chamado->data_resolvido ? (\Carbon\Carbon::parse($chamado->data_resolvido)->lte($chamado->prazo_solucao) ? 'status-resolvido-em-dia' : 'status-resolvido-atrasado') : '' }}">
                            {{ $chamado->data_resolvido ? \Carbon\Carbon::parse($chamado->data_resolvido)->format('d/m/Y H:i:s') : '-' }}
                        </td>
                        <td>
                            <select name="situacao"
                                data-chamado-id="{{ $chamado->id }}"
                                data-situacao-atual="{{ $chamado->ultimaSituacao->id }}"
                                data-original-value="{{ $chamado->ultimaSituacao->id }}"
                                onchange="atualizarSituacao(this, '{{$chamado->id}}')"
                                {{ $chamado->ultimaSituacao->nome == 'Resolvido' ? 'disabled' : '' }}>

                                @foreach ($situacoes as $situacaoOpcao)
                                    @if ($situacaoOpcao->id !== 1)
                                        <option value="{{ $situacaoOpcao->id }}"
                                            {{ $chamado->ultimaSituacao->id == $situacaoOpcao->id ? 'selected' : '' }}>
                                            {{ $situacaoOpcao->nome }}
                                        </option>
                                    @else
                                        @if ($chamado->ultimaSituacao->id == 1)
                                            <option value="1" selected>Novo</option>
                                        @else
                                            <option value="1">Novo</option>
                                        @endif
                                    @endif
                                @endforeach
                            </select>
                        </td>
                        <td class="action-cell">
                            <a id="editBtn" class="action-button" href="{{ route('chamados.edit', $chamado->id) }}" alt="edit"> 🖊 </a>
                            <form action="{{ route('chamados.destroy', $chamado->id) }}" method="POST" class="delete-form">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-button" onclick="event.stopPropagation(); return confirm('Tem certeza que deseja excluir este chamado?') ? true : false"> ❌ </button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
    <script src="{{asset('/js/index-functions.js')}}"></script>
</body>
</html>
