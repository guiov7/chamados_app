<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Chamado</title>
</head>
<body>
    <h1>Novo Chamado</h1>

    <form action="{{ route('chamados.store') }}" method="POST">
        @csrf

        <div>
            <label for="titulo">Título:</label>
            <input type="text" id="titulo" name="titulo" required>
        </div>

        <div>
            <label for="categoria_id">Categoria:</label>
            <select id="categoria_id" name="categoria_id" required>
                <option value="">Selecione a Categoria</option>
                @foreach ($categorias as $categoria)
                    <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                @endforeach
            </select>
        </div>

        <div>
            <label for="descricao">Descrição:</label>
            <textarea id="descricao" name="descricao" rows="5" required></textarea>
        </div>

        <div>
            <label for="prazo_solucao">Prazo de Solução:</label>
            <input type="date" id="prazo_solucao" name="prazo_solucao" disabled readonly>
            <small>Será preenchido automaticamente.</small>
        </div>

        <div>
            <label for="situacao_id">Situação:</label>
            <input type="text" id="situacao_nome" name="situacao_nome" value="Novo" disabled readonly>
            <input type="hidden" id="situacao_id" name="situacao_id" value="{{ $situacoes->where('nome', 'Novo')->first()->id ?? '' }}">
            <small>Definida automaticamente como "Novo".</small>
        </div>

        <div>
            <label for="data_criacao">Data de Criação:</label>
            <input type="datetime-local" id="data_criacao" name="data_criacao" disabled readonly>
            <small>Será preenchida automaticamente.</small>
        </div>

        <button type="submit">Salvar Chamado</button>
        <a href="{{ route('chamados.index') }}">Cancelar</a>
    </form>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dataCriacaoInput = document.getElementById('data_criacao');
            const prazoSolucaoInput = document.getElementById('prazo_solucao');
            const hoje = new Date();

            const dataCriacaoFormatada = hoje.toISOString().slice(0, 16); // data de criação = data atual automatica
            dataCriacaoInput.value = dataCriacaoFormatada;

            const prazo = new Date(hoje);
            prazo.setDate(hoje.getDate() + 3); // prazo de solução (+ 3 dias corridos)
            const prazoFormatado = prazo.toISOString().slice(0, 10);
            prazoSolucaoInput.value = prazoFormatado;
        });
    </script>
</body>
</html>
