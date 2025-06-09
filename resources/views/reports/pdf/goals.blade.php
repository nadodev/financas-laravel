<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Objetivos Financeiros</title>
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
        .status { padding: 4px 8px; border-radius: 4px; font-size: 12px; }
        .status-completed { background-color: #d1fae5; color: #065f46; }
        .status-in-progress { background-color: #dbeafe; color: #1e40af; }
        .status-cancelled { background-color: #fee2e2; color: #991b1b; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Objetivos Financeiros</h1>
        <p>Gerado em: {{ $generated_at }}</p>
    </div>

    <div class="summary">
        <div class="summary-item">
            <strong>Total de Objetivos:</strong> {{ $totalGoals }}
        </div>
        <div class="summary-item">
            <strong>Valor Total:</strong> R$ {{ number_format($totalAmount, 2, ',', '.') }}
        </div>
        <div class="summary-item">
            <strong>Valor Atual:</strong> R$ {{ number_format($currentAmount, 2, ',', '.') }}
        </div>
    </div>

    <div class="section">
        <h2>Análise de Progresso</h2>
        <table>
            <thead>
                <tr>
                    <th>Status</th>
                    <th>Quantidade</th>
                    <th>Porcentagem</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Em Andamento</td>
                    <td class="text-right">{{ $progressAnalysis['in_progress'] }}</td>
                    <td class="text-right">{{ $totalGoals > 0 ? number_format(($progressAnalysis['in_progress'] / $totalGoals) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Concluídos</td>
                    <td class="text-right">{{ $progressAnalysis['completed'] }}</td>
                    <td class="text-right">{{ $totalGoals > 0 ? number_format(($progressAnalysis['completed'] / $totalGoals) * 100, 1) : 0 }}%</td>
                </tr>
                <tr>
                    <td>Cancelados</td>
                    <td class="text-right">{{ $progressAnalysis['cancelled'] }}</td>
                    <td class="text-right">{{ $totalGoals > 0 ? number_format(($progressAnalysis['cancelled'] / $totalGoals) * 100, 1) : 0 }}%</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="section">
        <h2>Lista de Objetivos</h2>
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Meta</th>
                    <th>Atual</th>
                    <th>Progresso</th>
                    <th>Status</th>
                    <th>Data Início</th>
                    <th>Data Final</th>
                </tr>
            </thead>
            <tbody>
                @foreach($goals as $goal)
                    <tr>
                        <td>{{ $goal->name }}</td>
                        <td class="text-right">R$ {{ number_format($goal->target_amount, 2, ',', '.') }}</td>
                        <td class="text-right">R$ {{ number_format($goal->current_amount, 2, ',', '.') }}</td>
                        <td class="text-right">
                            {{ $goal->target_amount > 0 ? number_format(min(100, ($goal->current_amount / $goal->target_amount) * 100), 1) : 0 }}%
                        </td>
                        <td>
                            <span class="status {{ $goal->status === 'completed' ? 'status-completed' : ($goal->status === 'cancelled' ? 'status-cancelled' : 'status-in-progress') }}">
                                {{ $goal::$statuses[$goal->status] ?? ucfirst($goal->status) }}
                            </span>
                        </td>
                        <td>{{ $goal->start_date ? $goal->start_date->format('d/m/Y') : '-' }}</td>
                        <td>{{ $goal->end_date ? $goal->end_date->format('d/m/Y') : '-' }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>
</html> 