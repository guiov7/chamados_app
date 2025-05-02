<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Listagem de Chamados</title>
    <link rel="stylesheet" href="{{asset('/css/chamados-list.css')}}">
</head>

<body>
    <h1>Listagem de Chamados</h1>
    <a id="btnNovo" href="{{ route('chamados.create') }}">Novo Chamado</a>
    <a id="btnHome" href="{{ route('home.index') }}">Homepage / SLA Rating</a>

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
                <td>{{ $chamado->ultimaSituacao->nome }}</td>
                <td>{{ \Carbon\Carbon::parse($chamado->data_criacao)->format('d/m/Y H:i:s') }}</td>
                <td>
                    <a href="{{ route('chamados.show', $chamado->id) }}">Ver</a>
                    <form action="{{ route('chamados.destroy', $chamado->id) }}" method="POST" style="display: inline-block;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Tem certeza que deseja excluir este chamado?')">Excluir</button>
                    </form>
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
            </tr>
            @endforeach
        </tbody>
    </table>
    @endif
    <script>
        function atualizarSituacao(selectElement, chamadoId) {
            const novaSituacaoId = selectElement.value;
            const originalValue = selectElement.dataset.originalValue;
            const novaSituacaoTexto = selectElement.options[selectElement.selectedIndex].textContent;

            if (confirm('Tem certeza que deseja alterar a situação deste chamado?')) {
                fetch(`/chamados/${chamadoId}/historico-situacao`, { // Nova rota POST
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                        },
                        body: JSON.stringify({
                            situacao_id: novaSituacaoId
                        }),
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            alert(data.message);
                            selectElement.options[selectElement.selectedIndex].textContent = novaSituacaoTexto;
                            selectElement.dataset.originalValue = novaSituacaoId;
                            selectElement.dataset.situacaoAtual = novaSituacaoId;
                        } else {
                            alert(data.message);
                            selectElement.value = originalValue;
                        }
                    })
                    .catch(error => {
                        console.error('Erro:', error);
                        alert('Ocorreu um erro ao atualizar a situação.');
                        selectElement.value = originalValue;
                    });
            } else {
                selectElement.value = originalValue;
            }
        }
    </script>
</body>

</html>