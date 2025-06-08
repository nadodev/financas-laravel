<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Contas</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .summary {
            margin-bottom: 30px;
        }
        .summary-item {
            margin-bottom: 10px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f5f5f5;
        }
        .positive {
            color: green;
        }
        .negative {
            color: red;
        }
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 12px;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Contas</h1>
    </div>

    <div class="summary">
        <h2>Resumo</h2>
        <div class="summary-item">
            <strong>Saldo Total:</strong>
            <span class="{{ $totalBalance >= 0 ? 'positive' : 'negative' }}">
                R$ {{ number_format($totalBalance, 2, ',', '.') }}
            </span>
        </div>
    </div>

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
                <td class="{{ $balance['current_balance'] >= 0 ? 'positive' : 'negative' }}">
                    R$ {{ number_format($balance['current_balance'], 2, ',', '.') }}
                </td>
                <td class="positive">
                    R$ {{ number_format($balance['total_income'], 2, ',', '.') }}
                </td>
                <td class="negative">
                    R$ {{ number_format($balance['total_expense'], 2, ',', '.') }}
                </td>
                <td>{{ $balance['transaction_count'] }}</td>
                <td>
                    R$ {{ number_format($balance['transaction_count'] > 0 ? 
                        ($balance['total_income'] + $balance['total_expense']) / $balance['transaction_count'] : 0, 
                        2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Relatório gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 