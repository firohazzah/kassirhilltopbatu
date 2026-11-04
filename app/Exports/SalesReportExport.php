<?php
namespace App\Exports;

use App\Models\Transaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesReportExport implements FromCollection, WithHeadings, WithMapping
{
    protected $from;
    protected $to;

    public function __construct($from, $to)
    {
        $this->from = $from;
        $this->to = $to;
    }

    public function collection()
    {
        return Transaction::whereBetween('created_at', [
            $this->from . ' 00:00:00',
            $this->to . ' 23:59:59'
        ])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Invoice',
            'Tanggal',
            'Metode Pembayaran',
            'Total',
            'Status'
        ];
    }

    public function map($transaction): array
    {
        return [
            $transaction->invoice_number,
            $transaction->created_at->format('d-m-Y H:i'),
            $transaction->payment_method,
            $transaction->total,
            $transaction->status
        ];
    }
}
