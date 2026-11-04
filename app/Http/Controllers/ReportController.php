<?php

namespace App\Http\Controllers;

use App\Exports\SalesReportExport;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        [$from, $to] = $this->dates($request);
        $query = Transaction::with('items')->where('status', 'paid')
            ->whereDate('created_at', '>=', $from)
            ->whereDate('created_at', '<=', $to);

        $transactions = (clone $query)->latest()->paginate(15)->withQueryString();
        $totalRevenue = (clone $query)->sum('total');
        $transactionCount = (clone $query)->count();

        return view('reports.index', compact('transactions', 'totalRevenue', 'transactionCount', 'from', 'to'));
    }

    public function export(Request $request)
    {
        [$from, $to] = $this->dates($request);
        return Excel::download(new SalesReportExport($from, $to), "laporan-penjualan-{$from}-sampai-{$to}.xlsx");
    }

    private function dates(Request $request): array
    {
        $validated = $request->validate([
            'from' => ['nullable', 'date'],
            'to' => ['nullable', 'date', 'after_or_equal:from'],
        ]);

        return [
            $validated['from'] ?? now()->startOfMonth()->toDateString(),
            $validated['to'] ?? now()->toDateString(),
        ];
    }
}
