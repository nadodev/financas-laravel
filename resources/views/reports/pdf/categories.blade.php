<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Relatório de Categorias</title>
    <style>
        @page {
            margin: 2.5cm 2cm;
        }
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            margin: 0;
            padding: 0;
            color: #2d3748;
            line-height: 1.5;
            font-size: 12px;
        }
        .header {
            text-align: center;
            margin-bottom: 2.5rem;
            border-bottom: 3px solid #4299e1;
            padding-bottom: 1rem;
        }
        .header h1 {
            color: #2b6cb0;
            font-size: 24px;
            margin: 0;
            padding: 0;
        }
        .period {
            text-align: center;
            color: #4a5568;
            margin-bottom: 2rem;
            font-size: 14px;
            background: #ebf8ff;
            padding: 8px;
            border-radius: 4px;
        }
        .section {
            margin-bottom: 2rem;
            page-break-inside: avoid;
        }
        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #2b6cb0;
            margin-bottom: 1rem;
            padding: 8px 0;
            border-bottom: 2px solid #bee3f8;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .section-title .total {
            color: #2b6cb0;
            font-size: 18px;
        }
        .category-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .category-item {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .category-item:last-child {
            border-bottom: none;
        }
        .category-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .category-name {
            font-weight: bold;
            color: #2d3748;
        }
        .category-info {
            color: #718096;
            font-size: 11px;
        }
        .category-amount {
            font-weight: bold;
            color: #2d3748;
        }
        .category-percentage {
            color: #718096;
            margin-left: 8px;
        }
        .progress-bar {
            width: 100%;
            height: 6px;
            background-color: #e2e8f0;
            border-radius: 3px;
            margin-top: 8px;
            position: relative;
        }
        .progress-bar-fill {
            height: 100%;
            border-radius: 3px;
            position: absolute;
            left: 0;
            top: 0;
        }
        .progress-bar-fill.income {
            background-color: #48bb78;
        }
        .progress-bar-fill.expense {
            background-color: #f56565;
        }
        .trend-item {
            padding: 12px 0;
            border-bottom: 1px solid #e2e8f0;
        }
        .trend-item:last-child {
            border-bottom: none;
        }
        .trend-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .trend-name {
            font-weight: bold;
            color: #2d3748;
        }
        .trend-values {
            color: #718096;
            font-size: 11px;
            margin-top: 4px;
        }
        .trend-percentage {
            font-weight: bold;
            padding: 2px 8px;
            border-radius: 12px;
            font-size: 11px;
        }
        .trend-percentage.positive {
            background-color: #c6f6d5;
            color: #2f855a;
        }
        .trend-percentage.negative {
            background-color: #fed7d7;
            color: #c53030;
        }
        .footer {
            margin-top: 2rem;
            text-align: center;
            font-size: 10px;
            color: #718096;
            border-top: 1px solid #e2e8f0;
            padding-top: 1rem;
            position: fixed;
            bottom: 0;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Relatório de Categorias</h1>
    </div>

    <div class="period">
        Período: {{ \Carbon\Carbon::parse($start_date)->format('d/m/Y') }} até {{ \Carbon\Carbon::parse($end_date)->format('d/m/Y') }}
    </div>

    @if($categoryIncome->isNotEmpty())
    <div class="section">
        <div class="section-title">
            <span>Receitas por Categoria</span>
            <span class="total">R$ {{ number_format($totalIncome, 2, ',', '.') }}</span>
        </div>
        <ul class="category-list">
            @foreach($categoryIncome as $category)
            <li class="category-item">
                <div class="category-header">
                    <div>
                        <span class="category-name">{{ $category['name'] }}</span>
                        <span class="category-info">{{ $category['count'] }} transações</span>
                    </div>
                    <div>
                        <span class="category-amount">R$ {{ number_format($category['total'], 2, ',', '.') }}</span>
                        <span class="category-percentage">{{ number_format($category['percentage'], 1) }}%</span>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-bar-fill income" style="width: {{ $category['percentage'] }}%"></div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($categoryExpense->isNotEmpty())
    <div class="section">
        <div class="section-title">
            <span>Despesas por Categoria</span>
            <span class="total">R$ {{ number_format($totalExpense, 2, ',', '.') }}</span>
        </div>
        <ul class="category-list">
            @foreach($categoryExpense as $category)
            <li class="category-item">
                <div class="category-header">
                    <div>
                        <span class="category-name">{{ $category['name'] }}</span>
                        <span class="category-info">{{ $category['count'] }} transações</span>
                    </div>
                    <div>
                        <span class="category-amount">R$ {{ number_format($category['total'], 2, ',', '.') }}</span>
                        <span class="category-percentage">{{ number_format($category['percentage'], 1) }}%</span>
                    </div>
                </div>
                <div class="progress-bar">
                    <div class="progress-bar-fill expense" style="width: {{ $category['percentage'] }}%"></div>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    @if($trends->isNotEmpty())
    <div class="section">
        <div class="section-title">
            <span>Análise de Tendências</span>
        </div>
        <ul class="category-list">
            @foreach($trends as $trend)
            <li class="trend-item">
                <div class="trend-header">
                    <div>
                        <div class="trend-name">{{ $trend['category_name'] }}</div>
                        <div class="trend-values">
                            R$ {{ number_format($trend['previous_total'], 2, ',', '.') }}
                            →
                            R$ {{ number_format($trend['current_total'], 2, ',', '.') }}
                        </div>
                    </div>
                    <span class="trend-percentage {{ $trend['change_percentage'] >= 0 ? 'positive' : 'negative' }}">
                        {{ $trend['change_percentage'] >= 0 ? '+' : '' }}{{ number_format($trend['change_percentage'], 1) }}%
                    </span>
                </div>
            </li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="footer">
        Relatório gerado em {{ $generated_at }}
    </div>
</body>
</html> 