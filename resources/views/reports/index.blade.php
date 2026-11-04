@extends('layouts.app')
@section('title', 'Laporan Penjualan')
@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4"><div><h1 class="h3 page-title mb-1">Laporan Penjualan</h1><div class="text-secondary">Ringkasan dan rincian transaksi berdasarkan periode.</div></div><a class="btn btn-success" href="{{ route('laporan.export',['from'=>$from,'to'=>$to]) }}">↓ Ekspor Excel (.xlsx)</a></div>
<div class="card p-3 p-md-4 mb-4"><form method="GET" action="{{ route('laporan.index') }}" class="row g-3 align-items-end"><div class="col-md-4"><label class="form-label">Dari tanggal</label><input type="date" name="from" class="form-control" value="{{ $from }}" required></div><div class="col-md-4"><label class="form-label">Sampai tanggal</label><input type="date" name="to" class="form-control" value="{{ $to }}" required></div><div class="col-md-4"><button class="btn btn-coffee w-100">Tampilkan Laporan</button></div></form></div>
<div class="row g-3 mb-4"><div class="col-md-6"><div class="card p-4"><div class="text-secondary">Total Omzet</div><div class="fs-3 fw-bold">Rp {{ number_format($totalRevenue,0,',','.') }}</div></div></div><div class="col-md-6"><div class="card p-4"><div class="text-secondary">Jumlah Transaksi</div><div class="fs-3 fw-bold">{{ number_format($transactionCount) }}</div></div></div></div>
<div class="card p-3 p-md-4"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>No. Nota</th><th>Tanggal</th><th>Rincian Menu</th><th>Pembayaran</th><th class="text-end">Total</th></tr></thead><tbody>
@forelse($transactions as $trx)
<tr><td>{{ $trx->invoice_number }}</td><td>{{ $trx->created_at->format('d/m/Y H:i') }}</td><td>@foreach($trx->items as $item)<div>{{ $item->product_name }} × {{ $item->quantity }}</div>@endforeach</td><td>{{ strtoupper($trx->payment_method) }}</td><td class="text-end">Rp {{ number_format($trx->total,0,',','.') }}</td></tr>
@empty <tr><td colspan="5" class="text-center text-secondary py-4">Tidak ada transaksi pada periode ini.</td></tr> @endforelse
</tbody></table></div>{{ $transactions->links() }}</div>
<div class="small text-secondary mt-3">File Excel berisi rincian per item. Total transaksi dan diskon akan ditampilkan pada setiap baris item dari transaksi yang sama.</div>
@endsection
