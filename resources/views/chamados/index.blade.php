<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Chamados</title>
</head>

<body>
    <h1>Listagem de Chamados</h1>
    <a href="{{ route('chamados.create') }}">Novo Chamado</a>

    @if (session('success'))
        <div style="color: green;">{{ session('success') }}</div>
    @endif

    @if ($chamados->isEmpty())
        <p>Nenhum chamado cadastrado.</p>
    @else
        <table>
            <thead>
                <tr>
                    <th>Título</th>
                    <th>Categoria</th>
                    <th>Prazo de Solução</th>
                    <th>Situação</th>
                    <th>Data de Criação</th>
                    <th>Ações</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($chamados as $chamado)
                    <tr>
                        <td>{{ $chamado->titulo }}</td>
                        <td>{{ $chamado->categoria->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($chamado->prazo_solucao)->format('d/m/Y') }}</td>
                        <td>{{ $chamado->situacao->nome }}</td>
                        <td>{{ \Carbon\Carbon::parse($chamado->data_criacao)->format('d/m/Y H:i:s') }}</td>
                        <td>
                            <a href="{{ route('chamados.show', $chamado->id) }}">Ver</a>
                            <a href="#">Excluir</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
</body>

</html>
