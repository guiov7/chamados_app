<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detalhes do Chamado</title>
</head>
<body>
    <h1>Detalhes do Chamado</h1>

    <div>
        <strong>Título:</strong> {{ $chamado->titulo }}
    </div>

    <div>
        <strong>Categoria:</strong> {{ $chamado->categoria->nome }}
    </div>

    <div>
        <strong>Descrição:</strong>
        <p>{{ $chamado->descricao }}</p>
    </div>

    <div>
        <strong>Prazo de Solução:</strong> {{ \Carbon\Carbon::parse($chamado->prazo_solucao)->format('d/m/Y') }}
    </div>

    <div>
        <strong>Situação:</strong> {{ $chamado->situacao->nome }}
    </div>

    <div>
        <strong>Data de Criação:</strong> {{ \Carbon\Carbon::parse($chamado->data_criacao)->format('d/m/Y H:i:s') }}
    </div>

    @isset($chamado->data_solucao)
        <div>
            <strong>Data de Solução:</strong> {{ \Carbon\Carbon::parse($chamado->data_solucao)->format('d/m/Y H:i:s') }}
        </div>
    @endisset

    <a href="{{ route('chamados.index') }}">Voltar para a Listagem</a>
</body>
</html>
