<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório por Categorias</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 30px; }
        .summary { margin-bottom: 30px; }
        .summary-item { margin-bottom: 10px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f8f9fa; }
        .text-green { color: #059669; }
        .text-red { color: #dc2626; }
        .text-right { text-align: right; }
        .section { margin-bottom: 30px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório por Categorias</h1>
        <p>Período: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}</p>
        <p>Gerado em: {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total de Receitas:</strong> R$ {{ number_format($totalIncome, 2, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total de Despesas:</strong> R$ {{ number_format($totalExpense, 2, ',', '.') }}
        </div>
    </div>

    @if($categoryIncome->isNotEmpty())
    <div class="section">
        <h2>Receitas por Categoria</h2>
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Total</th>
                    <th>Porcentagem</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryIncome as $category)
                    <tr>
                        <td>{{ $category['name'] }}</td>
                        <td class="text-right">R$ {{ number_format($category['total'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($category['percentage'], 1) }}%</td>
                        <td class="text-right">{{ $category['count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($categoryExpense->isNotEmpty())
    <div class="section">
        <h2>Despesas por Categoria</h2>
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Total</th>
                    <th>Porcentagem</th>
                    <th>Quantidade</th>
                </tr>
            </thead>
            <tbody>
                @foreach($categoryExpense as $category)
                    <tr>
                        <td>{{ $category['name'] }}</td>
                        <td class="text-right">R$ {{ number_format($category['total'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($category['percentage'], 1) }}%</td>
                        <td class="text-right">{{ $category['count'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    @if($trends->isNotEmpty())
    <div class="section">
        <h2>Análise de Tendências</h2>
        <table>
            <thead>
                <tr>
                    <th>Categoria</th>
                    <th>Período Anterior</th>
                    <th>Período Atual</th>
                    <th>Variação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($trends as $trend)
                    <tr>
                        <td>{{ $trend['category_name'] }}</td>
                        <td class="text-right">R$ {{ number_format($trend['previous_total'], 2, ',', '.') }}</td>
                        <td class="text-right">R$ {{ number_format($trend['current_total'], 2, ',', '.') }}</td>
                        <td class="text-right">{{ number_format($trend['change_percentage'], 1) }}%</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif
</body>
</html> 