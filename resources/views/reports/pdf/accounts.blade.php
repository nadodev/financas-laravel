<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Contas</title>
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
        <h1>Relatório de Contas</h1>
        <p>Gerado em: {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Saldo Total:</strong>
            <span class="{{ $totalBalance >= 0 ? 'text-green' : 'text-red' }}">
                R$ {{ number_format($totalBalance, 2, ',', '.') }}
            </span>
        </div>
    </div>

    <div class="section">
        <h2>Detalhamento por Conta</h2>
        <table>
            <thead>
                <tr>
                    <th>Conta</th>
                    <th>Saldo Atual</th>
                    <th>Total de Receitas</th>
                    <th>Total de Despesas</th>
                    <th>Quantidade de Transações</th>
                    <th>Média por Transação</th>
                </tr>
            </thead>
            <tbody>
                @foreach($balances as $balance)
                    <tr>
                        <td>{{ $balance['name'] }}</td>
                        <td class="text-right {{ $balance['current_balance'] >= 0 ? 'text-green' : 'text-red' }}">
                            R$ {{ number_format($balance['current_balance'], 2, ',', '.') }}
                        </td>
                        <td class="text-right text-green">
                            R$ {{ number_format($balance['total_income'], 2, ',', '.') }}
                        </td>
                        <td class="text-right text-red">
                            R$ {{ number_format($balance['total_expense'], 2, ',', '.') }}
                        </td>
                        <td class="text-right">
                            {{ $balance['transaction_count'] }}
                        </td>
                        <td class="text-right">
                            R$ {{ number_format($balance['transaction_count'] > 0 ? 
                                ($balance['total_income'] + $balance['total_expense']) / $balance['transaction_count'] : 0, 
                                2, ',', '.') }}
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 