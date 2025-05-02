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
                            <form action="{{ route('chamados.destroy', $chamado->id) }}" method="POST"
                                style="display: inline-block;">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                    onclick="return confirm('Tem certeza que deseja excluir este chamado?')">Excluir</button>
                            </form>
                            <select name="situacao" onchange="atualizarSituacao(this, {{ $chamado->id }})">
                                <option value="{{ $chamado->situacao->id }}" selected>{{ $chamado->situacao->nome }}
                                </option>
                                @foreach ($situacoes as $situacaoOpcao)
                                    @if (($situacaoOpcao->nome === 'Pendente' || $situacaoOpcao->nome === 'Resolvido') && $situacaoOpcao->id !== $chamado->situacao->id)
                                        <option value="{{ $situacaoOpcao->id }}">{{ $situacaoOpcao->nome }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endif
    <script>
        function atualizarSituacao(selectElement, chamadoId) {
            const novaSituacaoId = selectElement.value;
            if (confirm('Tem certeza que deseja alterar a situação deste chamado?')) {
                fetch(`/chamados/${chamadoId}/atualizar-situacao`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    },
                    body: JSON.stringify({ situacao_id: novaSituacaoId }),
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            window.location.reload(); // refresh na página para atualizar
                        } else {
                            alert(data.message);
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Ocorreu um erro ao atualizar a situação.');
                    });
            } else {
                // reverte seleção se o usuário cancelar
                selectElement.value = selectElement.dataset.originalValue;
            }
            // valor original se o usuário cancelar
            selectElement.dataset.originalValue = selectElement.value;
        }

    </script>
</body>

</html>
