<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Receitas e Despesas</title>
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
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Receitas e Despesas</h1>
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
        <div class="summary-item">
            <strong>Saldo:</strong> R$ {{ number_format($balance, 2, ',', '.') }}
        </div>
    </div>

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
                    <td class="text-right">R$ {{ number_format($data['income'], 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($data['expense'], 2, ',', '.') }}</td>
                    <td class="text-right">R$ {{ number_format($data['balance'], 2, ',', '.') }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Transações</h2>
    <table>
        <thead>
            <tr>
                <th>Data</th>
                <th>Descrição</th>
                <th>Categoria</th>
                <th>Valor</th>
                <th>Tipo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transactions as $transaction)
                <tr>
                    <td>{{ $transaction->date->format('d/m/Y') }}</td>
                    <td>{{ $transaction->description }}</td>
                    <td>{{ $transaction->category->name }}</td>
                    <td class="text-right">R$ {{ number_format($transaction->amount, 2, ',', '.') }}</td>
                    <td>{{ $transaction->type === 'income' ? 'Receita' : 'Despesa' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html> 