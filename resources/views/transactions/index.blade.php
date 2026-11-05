@extends('layouts.app')
@section('title', 'Riwayat Transaksi')
@section('content')
<div class="mb-4"><h1 class="h3 page-title mb-1">Riwayat Transaksi</h1><div class="text-secondary">Semua transaksi yang tercatat.</div></div>
<div class="card p-3 p-md-4"><div class="table-responsive"><table class="table align-middle"><thead><tr><th>No. Nota</th><th>Tanggal</th><th>Pelanggan</th><th>Pembayaran</th><th class="text-end">Total</th><th></th></tr></thead><tbody>
    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">

    <div class="d-flex align-items-center gap-2">
        <span class="text-secondary small">Tampilkan</span>

        <select class="form-select form-select-sm"
                style="width: 85px;"
                onchange="window.location.href='?per_page=' + this.value">

            @foreach([10, 20, 30, 50, 100] as $limit)
                <option value="{{ $limit }}"
                    {{ $perPage == $limit ? 'selected' : '' }}>
                    {{ $limit }}
                </option>
            @endforeach

        </select>

        <span class="text-secondary small">data</span>
    </div>

</div>
@forelse($transactions as $trx)
<tr><td class="fw-semibold">{{ $trx->invoice_number }}</td><td>{{ $trx->created_at->format('d/m/Y H:i') }}</td><td>{{ $trx->customer_name ?: '-' }}</td><td>{{ strtoupper($trx->payment_method) }}</td><td class="text-end money">Rp {{ number_format($trx->total,0,',','.') }}</td><td><a href="{{ route('transaksi.show',$trx) }}" class="btn btn-sm btn-outline-secondary">Detail</a></td></tr>
@empty <tr><td colspan="6" class="text-center py-4">Belum ada transaksi.</td></tr> @endforelse
</tbody></table></div>{{ $transactions->links() }}</div>
<div class="d-flex justify-content-between align-items-center flex-wrap gap-3 mt-3">

    <small class="text-secondary">
        Menampilkan {{ $transactions->firstItem() ?? 0 }}
        - {{ $transactions->lastItem() ?? 0 }}
        dari {{ $transactions->total() }} transaksi
    </small>

    {{ $transactions->links() }}

</div>
@endsection
