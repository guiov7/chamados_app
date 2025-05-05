<!DOCTYPE html>
<html lang="pt-BR">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Página Inicial</title>
    <link rel="stylesheet" href="{{asset('/css/global.css')}}">
    <link rel="stylesheet" href="{{asset('/css/home.css')}}">
</head>

<body>
    <div class="container">
        <h1 class="page-title">Painel de Controle</h1>

        @if ($mensagem)
            <div class="alert alert-info">{{ $mensagem }}</div>
        @endif

        <div class="dashboard-grid">
            <!-- <div class="dashboard-item">
                <h2 class="dashboard-item-title">Chamados Resolvidos Dentro do Prazo (Mês Atual)</h2>
                <p class="dashboard-item-value">{{ number_format($percentualResolvidosNoPrazo, 2) }}%</p>
            </div> -->

            <div class="dashboard-item">
                <h2 class="dashboard-item-title">Ações</h2>
                <p><a id="linkList" href="{{ route('chamados.index') }}" class="dashboard-link">Listagem de Chamados</a></p>
            </div>

            <form method="GET" action="{{ route('home.index') }}">
                <h3 class="dashboard-item-title">Filtrar por Mês</h3>
                <input type="month" name="mes" value="{{ $mesReferencia }}">
                <button type="submit" class="action-button">Filtrar</button>
            </form>
            <div id="specialArea" class="dashboard-item chart-container">
                <h2 class="dashboard-item-title dashboard-item-title">Chamados Resolvidos (Mês Atual)</h2>
                <canvas id="resolvedChart"></canvas>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const resolvedChartCanvas = document.getElementById('resolvedChart').getContext('2d');
        const resolvedChart = new Chart(resolvedChartCanvas, {
            type: 'pie',
            data: {
                labels: ['Resolvidos', 'Não Resolvidos'],
                datasets: [{
                    label: 'Chamados Resolvidos (Mês Atual)',
                    data: [{{ $chamadosResolvidosMesAtual }}, {{ $totalChamadosMesAtual - $chamadosResolvidosMesAtual }}],
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.8)', // Cor para resolvidos
                        'rgba(255, 99, 132, 0.8)', // Cor para não resolvidos
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(255, 99, 132, 1)',
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                height: 400, // Define uma altura fixa
            }
        });
    </script>
