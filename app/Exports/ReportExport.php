<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;
use Maatwebsite\Excel\Concerns\Exportable;

class ReportExport implements FromView, WithTitle
{
    use Exportable;
    
    protected $data;
    protected $sheetTitle;

    public function __construct($data)
    {
        $this->data = $data;
        $this->sheetTitle = $this->generateSheetTitle($data['reportType']);
    }

    public function view(): View
    {
        return view("reports.pdf.{$this->data['reportType']}", $this->data);
    }

    public function title(): string
    {
        return $this->sheetTitle;
    }

    private function generateSheetTitle(string $reportType): string
    {
        $titles = [
            'income-expense' => 'Rec_Desp',
            'categories' => 'Categorias',
            'goals' => 'Objetivos',
            'accounts' => 'Contas'
        ];

        $title = $titles[$reportType] ?? 'Relatório';
        return substr($title, 0, 31);
    }
} 