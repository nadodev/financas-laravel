<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Receitas e Despesas</title>
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
        .income {
            color: green;
        }
        .expense {
            color: red;
        }
        .monthly-analysis {
            margin-top: 30px;
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
        <h1>Relatório de Receitas e Despesas</h1>
        <p>Período: {{ Carbon\Carbon::parse($transactions->first()->date)->format('d/m/Y') }} a {{ Carbon\Carbon::parse($transactions->last()->date)->format('d/m/Y') }}</p>
    </div>

    <div class="summary">
        <h2>Resumo</h2>
        <div class="summary-item">
            <strong>Total de Receitas:</strong> R$ {{ number_format($totalIncome, 2, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Total de Despesas:</strong> R$ {{ number_format($totalExpense, 2, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Saldo:</strong> R$ {{ number_format($balance, 2, ',', '.') }}
        </div>
    </div>

    <h2>Transações</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Tipo</th>
                <th>Valor</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
            <tr>
                <td>{{ $transaction->date->format('d/m/Y') }}</td>
                <td>{{ $transaction->description }}</td>
                <td>{{ $transaction->category->name }}</td>
                <td>{{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}</td>
                <td class="{{ $transaction->type === 'income' ? 'income' : 'expense' }}">
                    R$ {{ number_format($transaction->amount, 2, ',', '.') }}
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="monthly-analysis">
        <h2>Análise Mensal</h2>
        <table>
            <thead>
                <tr>
                    <th>Mês</th>
                    <th>Receitas</th>
                    <th>Despesas</th>
                    <th>Saldo</th>
                </tr>
            </thead>
            <tbody>
                @foreach($monthlyAnalysis as $month => $data)
                <tr>
                    <td>{{ Carbon\Carbon::createFromFormat('Y-m', $month)->format('M/Y') }}</td>
                    <td class="income">R$ {{ number_format($data['income'], 2, ',', '.') }}</td>
                    <td class="expense">R$ {{ number_format($data['expense'], 2, ',', '.') }}</td>
                    <td class="{{ $data['balance'] >= 0 ? 'income' : 'expense' }}">
                        R$ {{ number_format($data['balance'], 2, ',', '.') }}
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="footer">
        <p>Relatório gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 