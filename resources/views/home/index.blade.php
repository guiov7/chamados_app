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

        <div class="dashboard-grid">
            <div class="dashboard-item">
                <h2 class="dashboard-item-title">Chamados Resolvidos Dentro do Prazo (Mês Atual)</h2>
                <p class="dashboard-item-value">{{ number_format($percentualResolvidosNoPrazo, 2) }}%</p>
            </div>

            <div class="dashboard-item">
                <h2 class="dashboard-item-title">OPÇÕES</h2>
                <p><a href="{{ route('chamados.index') }}" class="dashboard-link">Listar Chamados</a></p>
            </div>

            </div>
    </div>
</body>
</html>
