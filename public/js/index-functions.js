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

function filtrarChamados() {
    const titulo = document.getElementById('filter-titulo').value;
    const categoriaId = document.getElementById('filter-categoria').value;
    const dataCriacao = document.getElementById('filter-data-criacao').value;
    const prazoSolucao = document.getElementById('filter-prazo-solucao').value;
    const concluido = document.getElementById('filter-concluido').value;
    const situacaoId = document.getElementById('filter-situacao').value;

    const filtros = {
        titulo: titulo,
        categoria_id: categoriaId,
        data_criacao: dataCriacao,
        prazo_solucao: prazoSolucao,
        concluido: concluido,
        situacao_id: situacaoId,
        _token: document.querySelector('meta[name="csrf-token"]').getAttribute('content') // Ou '{{ csrf_token() }}' se estiver diretamente no script
    };

    fetch('/chamados/filtrar', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
        },
        body: JSON.stringify(filtros), // Enviar os filtros como JSON no corpo
    })
    .then(response => response.json())
    .then(data => {
        const tableBody = document.getElementById('chamados-table-body');
        tableBody.innerHTML = ''; // Limpa a tabela

        if (data.length > 0) {
            data.forEach(chamado => {
                const row = `
                    <tr class="clickable-row" onclick="window.location='/chamados/${chamado.id}';" style="cursor: pointer;">
                        <td>${chamado.titulo}</td>
                        <td>${chamado.categoria.nome}</td>
                        <td>${new Date(chamado.data_criacao).toLocaleDateString()} ${new Date(chamado.data_criacao).toLocaleTimeString()}</td>
                        <td>${new Date(chamado.prazo_solucao).toLocaleDateString()}</td>
                        <td class="${chamado.data_resolvido ? (new Date(chamado.data_resolvido) <= new Date(chamado.prazo_solucao) ? 'status-resolvido-em-dia' : 'status-resolvido-atrasado') : ''}">
                            ${chamado.data_resolvido ? new Date(chamado.data_resolvido).toLocaleDateString() + ' ' + new Date(chamado.data_resolvido).toLocaleTimeString() : '-'}
                        </td>
                        <td>
                            <select name="situacao"
                                data-chamado-id="${chamado.id}"
                                data-situacao-atual="${chamado.situacao_id}"
                                data-original-value="${chamado.situacao_id}"
                                onchange="atualizarSituacao(this, '${chamado.id}')"
                                ${chamado.ultima_situacao.nome === 'Resolvido' ? 'disabled' : ''}>
                                ${situacoes.map(situacao => `
                                    ${situacao.id !== 1 ? `
                                        <option value="${situacao.id}" ${chamado.situacao_id == situacao.id ? 'selected' : ''}>${situacao.nome}</option>
                                    ` : `
                                        ${chamado.situacao_id == 1 ? `<option value="1" selected>Novo</option>` : `<option value="1">Novo</option>`}
                                    `}
                                `).join('')}
                            </select>
                        </td>
                        <td class="action-cell">
                            <a id="editBtn" class="action-button" href="/chamados/${chamado.id}/edit" alt="edit"> 🖊 </a>
                            <form action="/chamados/${chamado.id}" method="POST" class="delete-form">
                                <input type="hidden" name="_token" value="{{ csrf_token() }}">
                                <input type="hidden" name="_method" value="DELETE">
                                <button type="submit" class="delete-button" onclick="event.stopPropagation(); return confirm('Tem certeza que deseja excluir este chamado?') ? true : false" alt="delete"> ❌ </button>
                            </form>
                        </td>
                    </tr>
                `;
                tableBody.innerHTML += row;
            });
        } else {
            tableBody.innerHTML = '<tr><td colspan="7" class="empty-message">Nenhum chamado encontrado com os filtros aplicados.</td></tr>';
        }
    })
    .catch(error => {
        console.error('Erro:', error);
        alert('Ocorreu um erro ao filtrar os chamados.');
    });
}

function limparFiltros() {
    document.getElementById('filter-titulo').value = '';
    document.getElementById('filter-categoria').value = '';
    document.getElementById('filter-data-criacao').value = '';
    document.getElementById('filter-prazo-solucao').value = '';
    document.getElementById('filter-concluido').value = '';
    document.getElementById('filter-situacao').value = '';
    filtrarChamados(); // Refaz a filtragem sem parâmetros
}
