@extends('layouts.app')
@section('page', 'Transaksi')
@section('content')
    @if ($message = Session::get('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-check-circle mr-2"></i>
                <span>{{ $message }}</span>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif ($message = Session::get('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <div class="d-flex align-items-center">
                <i class="fas fa-exclamation-circle mr-2"></i>
                <span>{{ $message }}</span>
            </div>
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    <!-- Header -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <h2 class="h4 text-gray-900 font-weight-bold mb-2">Transaksi Penjualan</h2>
            <p class="text-gray-600 mb-0">
                Pantau semua transaksi yang terjadi di sistem kasir Anda di sini.
            </p>
        </div>
    </div>

    <div class="card shadow border-0">
        <!-- Card Header -->
        <div class="card-header bg-white py-4 border-bottom-subtle">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-receipt mr-2"></i>Daftar Transaksi
                    </h6>
                    <small class="text-muted d-block mt-2">
                        Total: <strong>{{ $transactions->total() }}</strong> transaksi
                    </small>
                </div>
            </div>
        </div>

        <!-- Filter / Search -->
        <div class="card-body border-bottom py-3">
            <form action="{{ route('admin.transactions.index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    <!-- Search Invoice / kasir -->
                    <div class="col-lg-4">
                        <label for="search" class="form-label small text-muted mb-1">
                            <i class="fas fa-search mr-1"></i>Invoice / Kasir
                        </label>
                        <input type="text"
                            name="search"
                            id="search"
                            class="form-control form-control-sm"
                            placeholder="INV-20xx... atau nama kasir..."
                            value="{{ request('search') }}">
                    </div>

                    <!-- Dari tanggal -->
                    <div class="col-lg-2">
                        <label for="date_from" class="form-label small text-muted mb-1">
                            <i class="fas fa-calendar mr-1"></i>Dari
                        </label>
                        <input type="date"
                            name="date_from"
                            id="date_from"
                            class="form-control form-control-sm"
                            value="{{ request('date_from') }}">
                    </div>

                    <!-- Sampai tanggal -->
                    <div class="col-lg-2">
                        <label for="date_to" class="form-label small text-muted mb-1">
                            <i class="fas fa-calendar mr-1"></i>Sampai
                        </label>
                        <input type="date"
                            name="date_to"
                            id="date_to"
                            class="form-control form-control-sm"
                            value="{{ request('date_to') }}">
                    </div>

                    <!-- Metode Pembayaran -->
                    <div class="col-lg-2">
                        <label for="payment_method" class="form-label small text-muted mb-1">
                            <i class="fas fa-money-bill mr-1"></i>Metode
                        </label>
                        <select name="payment_method" id="payment_method" class="form-control form-control-sm">
                            <option value="">Semua</option>
                            <option value="cash"    {{ request('payment_method')=='cash' ? 'selected' : '' }}>💵 Cash</option>
                            <option value="debit"   {{ request('payment_method')=='debit' ? 'selected' : '' }}>💳 Debit</option>
                            <option value="credit"  {{ request('payment_method')=='credit' ? 'selected' : '' }}>💳 Kredit</option>
                            <option value="ewallet" {{ request('payment_method')=='ewallet' ? 'selected' : '' }}>📱 E-Wallet</option>
                        </select>
                    </div>

                    <!-- Tombol Action -->
                    <div class="col-lg-2 d-flex gap-2">
                        @if(request()->hasAny(['search','payment_method','date_from','date_to']))
                            <a href="{{ route('admin.transactions.index') }}"
                               class="btn btn-sm btn-outline-secondary flex-grow-1"
                               title="Reset filter">
                                <i class="fas fa-undo"></i>
                            </a>
                        @endif
                        <button type="submit" class="btn btn-sm btn-primary flex-grow-1">
                            <i class="fas fa-search mr-1"></i>Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>



        <!-- Tabel Transaksi -->
        <div class="card-body p-0">
            @if($transactions->count())
                <div class="table-responsive">
                    <table class="table table-sm table-borderless mb-0 align-middle">
                        <thead class="bg-light">
                            <tr>
                                <th class="text-center text-muted font-weight-600 small py-2 px-3" style="width: 60px;">
                                    No
                                </th>
                                <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                    Invoice
                                </th>
                                <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                    Tanggal
                                </th>
                                <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                    Kasir
                                </th>
                                <th class="text-uppercase text-muted font-weight-600 small py-2 px-3 text-right">
                                    Total
                                </th>
                                <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                    Metode
                                </th>
                                <th class="text-center text-muted font-weight-600 small py-2 px-3" style="width: 140px;">
                                    Aksi
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                        @foreach($transactions as $index => $transaction)
                            <tr class="border-bottom">
                                <td class="text-center text-muted small py-2 px-3 align-middle">
                                    {{ ($transactions->currentPage() - 1) * $transactions->perPage() + $index + 1 }}
                                </td>
                                <td class="py-2 px-3 align-middle">
                                    <span class="font-weight-500 text-gray-900">
                                        {{ $transaction->invoice_code }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 align-middle">
                                    <span class="badge badge-light text-muted"
                                          style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                        <i class="far fa-calendar mr-1"></i>
                                        {{ $transaction->created_at->format('d M Y') }}
                                    </span>
                                    <small class="d-block text-muted mt-1">
                                        {{ $transaction->created_at->format('H:i') }}
                                    </small>
                                </td>
                                <td class="py-2 px-3 align-middle">
                                    <span class="text-muted small">
                                        {{ $transaction->user->name ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-right align-middle">
                                    <span class="font-weight-500">
                                        Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 align-middle">
                                    <span class="badge {{ $transaction->payment_badge_class }}">
                                        {{ $transaction->payment_label }}
                                    </span>
                                </td>
                                <td class="py-2 px-3 text-center align-middle">
                                    <a href="{{ route('admin.transactions.pdf', $transaction->invoice_code) }}"
                                       class="btn btn-sm btn-outline-secondary">
                                        <i class="fas fa-file-pdf mr-1"></i> Struk PDF
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-5 px-4">
                    <div class="mb-3">
                        <i class="fas fa-receipt fa-3x text-muted"></i>
                    </div>

                    @if(request()->hasAny(['search','payment_method','date_from','date_to']))
                        <h5 class="text-gray-600 mb-1">Tidak Ada Transaksi</h5>
                        <p class="text-muted mb-3">
                            Tidak ditemukan transaksi dengan filter yang diterapkan.
                        </p>
                        <a href="{{ route('admin.transactions.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-redo mr-1"></i> Reset Filter
                        </a>
                    @else
                        <h5 class="text-gray-600 mb-1">Belum Ada Data Transaksi</h5>
                        <p class="text-muted mb-3">
                            Transaksi akan muncul di sini setelah kasir melakukan penjualan.
                        </p>
                    @endif
                </div>
            @endif
        </div>

        <!-- Pagination -->
        @if ($transactions->hasPages())
            <div class="card-footer bg-light py-3 px-4 border-top-subtle">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Menampilkan
                            <strong>{{ $transactions->firstItem() }}</strong>–<strong>{{ $transactions->lastItem() }}</strong>
                            dari <strong>{{ $transactions->total() }}</strong> transaksi
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="text-right mt-3 mt-md-0">
                            {{ $transactions->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($transactions->count() > 0)
            <div class="card-footer bg-light py-3 px-4 border-top-subtle">
                <small class="text-muted">
                    Menampilkan <strong>{{ $transactions->count() }}</strong> transaksi
                </small>
            </div>
        @endif
    </div>

    {{-- Style kecil untuk table & card --}}
    <style>
        .table {
            margin-bottom: 0;
        }
        .table tbody tr {
            transition: background-color 0.2s ease;
        }
        .table tbody tr:hover {
            background-color: #f8f9fa;
        }
        .table thead th {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
            text-transform: uppercase;
        }
        .font-weight-500 {
            font-weight: 500;
        }
        .font-weight-600 {
            font-weight: 600;
        }
        .border-bottom-subtle {
            border-bottom: 1px solid #dee2e6 !important;
        }
        .border-top-subtle {
            border-top: 1px solid #dee2e6 !important;
        }
        .card-header,
        .card-footer {
            background-color: white !important;
        }
        .pagination {
            margin-bottom: 0;
        }
    </style>
@endsection
