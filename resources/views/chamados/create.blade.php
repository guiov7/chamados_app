<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Novo Chamado</title>
    <link rel="stylesheet" href="{{asset('/css/global.css')}}">
    <link rel="stylesheet" href="{{asset('/css/chamado-create.css')}}">
</head>

<body>
    <h1>Novo Chamado</h1>
    <div class="form-container">
        <form id="novoChamadoForm" >
            <div class="form-group">
                <label class="form-label" for="titulo">Título:</label>
                <input class="form-input" type="text" id="titulo" name="titulo" required>
            </div>

            <div class="form-group">
                <label class="form-label" for="categoria_id">Categoria:</label>
                <select class="form-select" id="categoria_id" name="categoria_id" required>
                    <option value="">Selecione a Categoria</option>
                    @foreach ($categorias as $categoria)
                        <option value="{{ $categoria->id }}">{{ $categoria->nome }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label class="form-label" for="descricao">Descrição:</label>
                <textarea class="form-textarea" id="descricao" name="descricao" rows="5" required></textarea>
            </div>

            <div style="display:none;">
                <label class="form-label" for="prazo_solucao">Prazo de Solução:</label>
                <input class="form-input" type="date" id="prazo_solucao" name="prazo_solucao">
            </div>

            <div style="display:none;">
                <label class="form-label" for="situacao_id">Situação:</label>
                <input class="form-input" type="hidden" id="situacao_id" name="situacao_id"
                    value="{{ $situacaoNovo->id ?? '' }}">
            </div>

            <div style="display:none;">
                <label class="form-label" for="data_criacao">Data de Criação:</label>
                <input class="form-input" type="datetime-local" id="data_criacao" name="data_criacao">
            </div>

            <button class="form-button" type="button" onclick="enviarChamado()">Criar Chamado</button>
            <a class="rc-btn" href="{{ route('chamados.index') }}" class="form-cancel-link">Cancelar</a>
        </form>
    </div>
    <div id="mensagem" class="error-message"></div>

    <script>
        function enviarChamado() {
            const form = document.getElementById('novoChamadoForm');
            const formData = new FormData(form);
            const data = {};
            formData.forEach((value, key) => {
                data[key] = value;
            });

            // Preenche os campos automáticos aqui no frontend
            const hoje = new Date();
            data['data_criacao'] = hoje.toISOString().slice(0, 16).replace('T', ' ');
            const prazo = new Date(hoje);
            prazo.setDate(hoje.getDate() + 3);
            data['prazo_solucao'] = prazo.toISOString().slice(0, 10);
            data['situacao_id'] = document.querySelector('input[name="situacao_id"]').value;

            console.log('Dados enviados:', data);
            fetch('/chamados', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                },
                body: JSON.stringify(data),
            })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        window.location.href = '/chamados'; // Redireciona para a listagem
                    } else {
                        alert(data.message);
                        selectElement.value = originalValue;
                        atualizarOpcoesSelect(selectElement, originalValue);
                    }
                })
                .catch(error => {
                    console.error('Erro:', error);
                    document.getElementById('mensagem').innerText = 'Ocorreu um erro ao enviar o chamado.';
                });
        }
    </script>
</body>

</html>
