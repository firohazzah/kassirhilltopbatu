@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('content')
<div class="mb-4"><h1 class="h3 page-title mb-1">Riwayat Transaksi</h1><div class="text-secondary">Semua transaksi yang tercatat.</div></div>
<div class="card p-3 p-md-4"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>No. Nota</th><th>Tanggal</th><th>Pelanggan</th><th>Pembayaran</th><th class="text-end">Total</th><th></th></tr></thead><tbody>
@forelse($transactions as $trx)
<tr><td class="fw-semibold">{{ $trx->invoice_number }}</td><td>{{ $trx->created_at->format('d/m/Y H:i') }}</td><td>{{ $trx->customer_name ?: '-' }}</td><td>{{ strtoupper($trx->payment_method) }}</td><td class="text-end money">Rp {{ number_format($trx->total,0,',','.') }}</td><td><a href="{{ route('transaksi.show',$trx) }}" class="btn btn-sm btn-outline-secondary">Detail</a></td></tr>
@empty <tr><td colspan="6" class="text-center py-4">Belum ada transaksi.</td></tr> @endforelse
</tbody></table></div>{{ $transactions->links() }}</div>
@endsection
