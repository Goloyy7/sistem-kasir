@extends('layouts.app')
@section('page', 'Dashboard')
@section('content')
    @if (session('error'))
        <div class="alert alert-warning alert-dismissible fade show mb-4" role="alert">
            {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @elseif (session('success'))
        <div class="alert alert-success alert-dismissible fade show mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif    
    
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <h2 class="h4 text-gray-900 font-weight-bold mb-2">Dashboard Sistem Kasir</h2>
            <p class="text-gray-600 mb-0">Statistik dan monitoring sistem kasir Anda secara real-time.</p>
        </div>
    </div>

    <!-- KPI Cards Row 1 - Management Stats -->
    <div class="row mb-4">
        <!-- Total Admin Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-primary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Total Admin</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalAdmin }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-user-tie fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Kasir Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-info shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-info text-uppercase mb-1">
                                Total Kasir</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalKasir }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-users fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Category Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-warning shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-warning text-uppercase mb-1">
                                Total Kategori</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalCategory }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-list fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Produk Card -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-success shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Total Produk</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalProduct }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-box fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- KPI Cards Row 2 - Transaction Stats -->
    <div class="row mb-4">
        <!-- Total Transaction Today -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Transaksi Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $transactionToday }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-receipt fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Revenue Today -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-danger shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-danger text-uppercase mb-1">
                                Omset Hari Ini</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($revenueToday, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Transaction All Time -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total Transaksi</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $totalTransactionAllTime }}</div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-chart-bar fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Revenue All Time -->
        <div class="col-xl-3 col-md-6 mb-4">
            <div class="card border-left-secondary shadow h-100 py-2">
                <div class="card-body">
                    <div class="row no-gutters align-items-center">
                        <div class="col mr-2">
                            <div class="text-xs font-weight-bold text-secondary text-uppercase mb-1">
                                Total Omset</div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($totalRevenueAllTime, 0, ',', '.') }}
                            </div>
                        </div>
                        <div class="col-auto">
                            <i class="fas fa-money-bill-wave fa-2x text-gray-300"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart & Statistics Section -->
    <div class="row mb-4">
        <!-- Transaction Chart (7 Days) -->
        <div class="col-xl-8 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-chart-line mr-2"></i>Transaksi 7 Hari Terakhir
                    </h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="transactionChart"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Method Stats -->
        <div class="col-xl-4 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-pie-chart mr-2"></i>Metode Pembayaran
                    </h6>
                </div>
                <div class="card-body">
                    <div style="height: 300px;">
                        <canvas id="paymentChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Tables Section -->
    <div class="row">
        <!-- Top Products Table -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-star mr-2"></i>Top 5 Produk Terlaris
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($topProducts->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-muted small py-2 px-3">Produk</th>
                                        <th class="text-muted small py-2 px-3 text-center">Terjual</th>
                                        <th class="text-muted small py-2 px-3 text-right">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($topProducts as $product)
                                        <tr class="border-bottom">
                                            <td class="py-2 px-3 align-middle">
                                                <span class="font-weight-500">{{ \Illuminate\Support\Str::limit($product->name, 20) }}</span>
                                            </td>
                                            <td class="py-2 px-3 text-center align-middle">
                                                <span class="badge badge-info">{{ $product->qty_sold }}</span>
                                            </td>
                                            <td class="py-2 px-3 text-right align-middle">
                                                <span class="font-weight-500 text-success">
                                                    Rp {{ number_format($product->revenue, 0, ',', '.') }}
                                                </span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Belum ada data transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Recent Transactions Table -->
        <div class="col-xl-6 mb-4">
            <div class="card shadow border-0">
                <div class="card-header bg-white py-3 border-bottom">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-history mr-2"></i>Transaksi Terbaru (10 Terakhir)
                    </h6>
                </div>
                <div class="card-body p-0">
                    @if($recentTransactions->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-sm table-borderless mb-0 align-middle">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="text-muted small py-2 px-3">Invoice</th>
                                        <th class="text-muted small py-2 px-3">Kasir</th>
                                        <th class="text-muted small py-2 px-3 text-right">Total</th>
                                        <th class="text-muted small py-2 px-3 text-center">Waktu</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentTransactions as $transaction)
                                        <tr class="border-bottom">
                                            <td class="py-2 px-3 align-middle">
                                                <span class="font-weight-500 text-primary">{{ $transaction->invoice_code }}</span>
                                            </td>
                                            <td class="py-2 px-3 align-middle">
                                                <small class="text-muted">{{ $transaction->user->name ?? '-' }}</small>
                                            </td>
                                            <td class="py-2 px-3 text-right align-middle">
                                                <span class="font-weight-500">
                                                    Rp {{ number_format($transaction->total_price, 0, ',', '.') }}
                                                </span>
                                            </td>
                                            <td class="py-2 px-3 text-center align-middle">
                                                <small class="text-muted">{{ $transaction->created_at->format('H:i') }}</small>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-4 text-muted">
                            <i class="fas fa-inbox fa-2x mb-2"></i>
                            <p>Belum ada transaksi</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Library -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
    
    <div id="chartData" 
         data-last7days="{{ json_encode($last7DaysData) }}"
         data-payment="{{ json_encode($paymentMethodStats) }}"
         style="display:none;"></div>
    
    <script>
        // Prepare data for charts
        const chartDataEl = document.getElementById('chartData');
        const last7DaysData = JSON.parse(chartDataEl.getAttribute('data-last7days'));
        const paymentMethodData = JSON.parse(chartDataEl.getAttribute('data-payment'));

        // Transaction Chart (Line Chart)
        const ctx = document.getElementById('transactionChart').getContext('2d');
        const transactionChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: last7DaysData.map(d => d.date),
                datasets: [
                    {
                        label: 'Jumlah Transaksi',
                        data: last7DaysData.map(d => d.count),
                        borderColor: '#4e73df',
                        backgroundColor: 'rgba(78, 115, 223, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#4e73df',
                    },
                    {
                        label: 'Revenue (Rp)',
                        data: last7DaysData.map(d => d.revenue / 1000000), // Dalam juta
                        borderColor: '#1cc88a',
                        backgroundColor: 'rgba(28, 200, 138, 0.1)',
                        borderWidth: 2,
                        tension: 0.4,
                        fill: true,
                        pointRadius: 5,
                        pointHoverRadius: 7,
                        pointBackgroundColor: '#1cc88a',
                        yAxisID: 'y1',
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                interaction: {
                    mode: 'index',
                    intersect: false,
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top',
                    },
                },
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        title: {
                            display: true,
                            text: 'Jumlah Transaksi'
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        title: {
                            display: true,
                            text: 'Revenue (Juta Rp)'
                        },
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                }
            }
        });

        // Payment Method Pie Chart
        const paymentCtx = document.getElementById('paymentChart').getContext('2d');
        const paymentChart = new Chart(paymentCtx, {
            type: 'doughnut',
            data: {
                labels: paymentMethodData.map(p => {
                    const labels = {
                        'cash': '💵 Cash',
                        'debit': '💳 Debit',
                        'credit': '💳 Credit',
                        'ewallet': '📱 E-Wallet'
                    };
                    return labels[p.payment_method] || p.payment_method;
                }),
                datasets: [{
                    data: paymentMethodData.map(p => p.total),
                    backgroundColor: [
                        '#4e73df',
                        '#1cc88a',
                        '#36b9cc',
                        '#f6c23e'
                    ],
                    borderColor: '#fff',
                    borderWidth: 2,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                    },
                }
            }
        });
    </script>
@endsection