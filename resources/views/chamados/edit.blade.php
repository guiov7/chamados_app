<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editar Chamado</title>
    <link rel="stylesheet" href="{{asset('/css/global.css')}}">
    <link rel="stylesheet" href="{{asset('/css/chamado-edit.css')}}">
</head>
<body>
    <div class="container">
        <h1 class="page-title">Editar Chamado</h1>

        <div class="form-container">
            <form id="editChamadoForm" method="POST" action="{{ route('chamados.update', $chamado->id) }}">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="titulo" class="form-label">Título:</label>
                    <input type="text" id="titulo" name="titulo" class="form-input" value="{{ $chamado->titulo }}" required>
                </div>

                <div class="form-group">
                    <label for="categoria_id" class="form-label">Categoria:</label>
                    <select id="categoria_id" name="categoria_id" class="form-select" required>
                        <option value="">Selecione a Categoria</option>
                        @foreach ($categorias as $categoria)
                            <option value="{{ $categoria->id }}" {{ $chamado->categoria_id == $categoria->id ? 'selected' : '' }}>{{ $categoria->nome }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label for="descricao" class="form-label">Descrição:</label>
                    <textarea id="descricao" name="descricao" rows="5" class="form-textarea" required>{{ $chamado->descricao }}</textarea>
                </div>

                <div style="display:none;">
                    <label for="prazo_solucao" class="form-label">Prazo de Solução:</label>
                    <input type="date" id="prazo_solucao" name="prazo_solucao" class="form-input" value="{{ $chamado->prazo_solucao }}">
                </div>

                <div style="display:none;">
                    <label for="situacao_id" class="form-label">Situação:</label>
                    <input type="hidden" id="situacao_id" name="situacao_id" class="form-input" value="{{ $chamado->situacao_id }}">
                </div>

                <div style="display:none;">
                    <label for="data_criacao" class="form-label">Data de Criação:</label>
                    <input type="datetime-local" id="data_criacao" name="data_criacao" class="form-input" value="{{ \Carbon\Carbon::parse($chamado->data_criacao)->format('Y-m-d\TH:i') }}">
                </div>

                <button type="submit" class="form-button">Atualizar Chamado</button>
                <a href="{{ route('chamados.index') }}" class="form-cancel-link">Cancelar</a>
            </form>

            <div id="mensagem" class="error-message">
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>
</body>

</html>
