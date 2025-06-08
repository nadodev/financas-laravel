<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Objetivos Financeiros</title>
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
        .progress-bar {
            width: 100%;
            background-color: #f5f5f5;
            padding: 3px;
            border-radius: 3px;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, .2);
        }
        .progress-bar-fill {
            display: block;
            height: 15px;
            background-color: #659cef;
            border-radius: 3px;
            transition: width 500ms ease-in-out;
        }
        .status {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }
        .status-completed {
            background-color: #d4edda;
            color: #155724;
        }
        .status-in-progress {
            background-color: #cce5ff;
            color: #004085;
        }
        .status-cancelled {
            background-color: #f8d7da;
            color: #721c24;
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
        <h1>Relatório de Objetivos Financeiros</h1>
    </div>

    <div class="summary">
        <h2>Resumo</h2>
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
                <td>{{ $progressAnalysis['in_progress'] }}</td>
                <td>
                    {{ number_format(($progressAnalysis['in_progress'] / $totalGoals) * 100, 2) }}%
                    <div class="progress-bar">
                        <span class="progress-bar-fill" style="width: {{ ($progressAnalysis['in_progress'] / $totalGoals) * 100 }}%"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Concluídos</td>
                <td>{{ $progressAnalysis['completed'] }}</td>
                <td>
                    {{ number_format(($progressAnalysis['completed'] / $totalGoals) * 100, 2) }}%
                    <div class="progress-bar">
                        <span class="progress-bar-fill" style="width: {{ ($progressAnalysis['completed'] / $totalGoals) * 100 }}%"></span>
                    </div>
                </td>
            </tr>
            <tr>
                <td>Cancelados</td>
                <td>{{ $progressAnalysis['cancelled'] }}</td>
                <td>
                    {{ number_format(($progressAnalysis['cancelled'] / $totalGoals) * 100, 2) }}%
                    <div class="progress-bar">
                        <span class="progress-bar-fill" style="width: {{ ($progressAnalysis['cancelled'] / $totalGoals) * 100 }}%"></span>
                    </div>
                </td>
            </tr>
        </tbody>
    </table>

    <h2>Objetivos</h2>
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
                <td>R$ {{ number_format($goal->target_amount, 2, ',', '.') }}</td>
                <td>R$ {{ number_format($goal->current_amount, 2, ',', '.') }}</td>
                <td>
                    {{ number_format($goal->progress_percentage, 2) }}%
                    <div class="progress-bar">
                        <span class="progress-bar-fill" style="width: {{ $goal->progress_percentage }}%"></span>
                    </div>
                </td>
                <td>
                    <span class="status status-{{ $goal->status }}">
                        {{ $goal::$statuses[$goal->status] }}
                    </span>
                </td>
                <td>{{ $goal->start_date ? $goal->start_date->format('d/m/Y') : '-' }}</td>
                <td>{{ $goal->end_date ? $goal->end_date->format('d/m/Y') : '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Relatório gerado em {{ now()->format('d/m/Y H:i:s') }}</p>
    </div>
</body>
</html> 