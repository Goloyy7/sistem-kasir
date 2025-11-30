{{-- resources/views/kasir/transaksi.blade.php --}}
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Transaksi Kasir - Sistem Kasir</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    {{-- Custom fonts for this template (SB Admin 2) --}}
    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet">

    {{-- SB Admin 2 CSS --}}
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        /* Custom styles untuk halaman Kasir Transaksi */
        body {
            background-color: #f8f9fc;
            font-size: 0.95rem;
        }

        .navbar {
            box-shadow: 0 2px 6px rgba(0,0,0,0.08);
        }

        .card {
            border: none;
            border-radius: 0.75rem;
            box-shadow: 0 4px 10px rgba(0,0,0,0.05);
        }

        .card-header {
            background: linear-gradient(90deg, #4e73df, #36b9cc);
            color: #fff;
            border-top-left-radius: 0.75rem;
            border-top-right-radius: 0.75rem;
        }

        .table thead th {
            background-color: #f1f3f9;
            border-bottom: none;
        }

        .badge-kasir {
            background-color: #4e73df;
            color: #fff;
        }

        .btn-main {
            background: linear-gradient(90deg, #4e73df, #1cc88a);
            border: none;
            color: #fff;
        }

        .btn-main:hover {
            opacity: 0.9;
            color: #fff;
        }

        .text-total {
            font-size: 1.3rem;
            font-weight: 600;
        }

        .small-label {
            font-size: 0.8rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.03em;
            color: #6c757d;
        }

        .page-wrapper {
            padding-top: 1.25rem;
            padding-bottom: 1.25rem;
        }

        @media (max-width: 768px) {
            .text-total {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>

{{-- Top Navbar --}}
<nav class="navbar navbar-expand-lg navbar-light bg-white">
    <div class="container-fluid">
        <span class="navbar-brand font-weight-bold">
            <i class="fas fa-cash-register mr-2"></i>Sistem Kasir
        </span>

        <div class="ml-auto d-flex align-items-center">
            <div class="mr-3 text-right">
                <div class="small-label mb-0">Kasir</div>
                <span class="badge badge-kasir">
                    {{ auth('user')->user()->name ?? 'Kasir' }}
                </span>
            </div>

            <div class="mr-3 text-right">
                <div class="small-label mb-0">Waktu</div>
                <span id="clock" class="font-weight-bold"></span>
            </div>

            {{-- Logout, sesuaikan route logout kamu --}}
            <form action="{{ route('logoutKasir') }}" method="POST" class="mb-0">
                @csrf
                <button type="submit" class="btn btn-outline-danger btn-sm">
                    <i class="fas fa-sign-out-alt mr-1"></i> Logout
                </button>
            </form>
        </div>
    </div>
</nav>

<div class="container-fluid page-wrapper">

    {{-- Alert / Notifikasi --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fas fa-check-circle mr-1"></i> {{ session('success') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fas fa-exclamation-circle mr-1"></i> {{ session('error') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif

    {{-- Form error validasi --}}
    @if($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Terjadi kesalahan:</strong>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="close" data-dismiss="alert"><span>&times;</span></button>
        </div>
    @endif

    <div class="row">
        {{-- Kiri: Input Kode Barang --}}
        <div class="col-lg-4 mb-4">
            <div class="card">
                <div class="card-header py-2">
                    <h6 class="mb-0"><i class="fas fa-barcode mr-2"></i>Input Barang</h6>
                </div>
                <div class="card-body">
                    <form action="{{ route('kasir.transaksi.add') }}" method="POST" autocomplete="off">
                        @csrf

                        <div class="form-group">
                            {{-- Label input kode barang, jelaskan bahwa kasir cukup input angka akhirnya saja --}}
                            <label for="kode_barang" class="small-label">
                                Kode Barang (input hanya angkanya saja)
                            </label>

                            <input type="text"
                                name="kode_barang"
                                id="kode_barang"
                                class="form-control"
                                placeholder="Contoh: 1 untuk PRD-00001"
                                >
                        </div>

                        {{-- Dropdown alternatif tanpa JavaScript --}}
                        @isset($products)
                            <label for="kode_barang_select" class="small-label mt-2">
                                Atau pilih dari daftar produk (opsional)
                            </label>
                            <select name="kode_barang_select" id="kode_barang_select" class="form-control">
                                <option value="">— Pilih produk (opsional) —</option>
                                @foreach ($products as $product)
                                    <option value="{{ $product->kode_barang }}">
                                        {{ $product->name }}
                                        ({{ $product->kode_barang }})
                                        — Stok: {{ $product->stock }}
                                    </option>
                                @endforeach
                            </select>
                            <small class="form-text text-muted">
                                Jika kasir memilih produk di sini, input kode di atas akan diabaikan.
                            </small>
                        @endisset


                        <div class="form-group">
                            <label for="qty" class="small-label">Jumlah</label>
                            <input type="number"
                                   name="qty"
                                   id="qty"
                                   class="form-control"
                                   min="1"
                                   value="1"
                                   required>
                        </div>

                        <button type="submit" class="btn btn-main btn-block">
                            <i class="fas fa-plus-circle mr-1"></i> Tambah ke Keranjang
                        </button>
                    </form>

                    <hr>

                    <small class="text-muted d-block">
                        <i class="fas fa-info-circle mr-1"></i>
                        Sistem ini menggunakan <strong>kode_barang</strong> sebagai input utama,
                        sehingga bisa diintegrasikan dengan barcode scanner.
                    </small>
                </div>
            </div>
        </div>

        {{-- Kanan: Keranjang & Pembayaran --}}
        <div class="col-lg-8 mb-4">
            <div class="card mb-3">
                <div class="card-header py-2 d-flex justify-content-between align-items-center">
                    <h6 class="mb-0"><i class="fas fa-shopping-cart mr-2"></i>Keranjang Belanja</h6>
                    <form action="{{ route('kasir.transaksi.clear') }}" method="POST"
                          onsubmit="return confirm('Kosongkan semua item di keranjang?');">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-secondary">
                            <i class="fas fa-trash-alt mr-1"></i> Kosongkan
                        </button>
                    </form>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                            <tr>
                                <th style="width: 5%;">No</th>
                                <th>Nama Barang</th>
                                <th class="text-center" style="width: 15%;">Qty</th>
                                <th class="text-right" style="width: 15%;">Harga</th>
                                <th class="text-right" style="width: 15%;">Subtotal</th>
                                <th class="text-center" style="width: 10%;">Aksi</th>
                            </tr>
                            </thead>
                            <tbody>
                            @php $no = 1; @endphp

                            @forelse($cart as $item)
                                <tr>
                                    <td>{{ $no++ }}</td>
                                    <td>
                                        <strong>{{ $item['product_name'] }}</strong><br>
                                        <small class="text-muted">ID: {{ $item['product_id'] }}</small>
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('kasir.transaksi.update', $item['product_id']) }}"
                                              method="POST" class="form-inline justify-content-center">
                                            @csrf
                                            <input type="number"
                                                   name="qty"
                                                   value="{{ $item['qty'] }}"
                                                   min="1"
                                                   class="form-control form-control-sm text-center"
                                                   style="width:70px;">
                                            <button type="submit"
                                                    class="btn btn-sm btn-link text-primary"
                                                    title="Update jumlah">
                                                <i class="fas fa-sync-alt"></i>
                                            </button>
                                        </form>
                                    </td>
                                    <td class="text-right">
                                        Rp {{ number_format($item['price'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-right">
                                        Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        <form action="{{ route('kasir.transaksi.remove', $item['product_id']) }}"
                                              method="POST"
                                              onsubmit="return confirm('Hapus item ini dari keranjang?');">
                                            @csrf
                                            <button type="submit"
                                                    class="btn btn-sm btn-outline-danger"
                                                    title="Hapus">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center text-muted py-4">
                                        <i class="fas fa-info-circle mr-1"></i>
                                        Keranjang masih kosong. Tambahkan barang menggunakan kode_barang.
                                    </td>
                                </tr>
                            @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Panel Pembayaran --}}
            <div class="card">
                <div class="card-body">
                    <div class="row align-items-center mb-3">
                        <div class="col-md-6 mb-2 mb-md-0">
                            <span class="small-label d-block">Total Belanja</span>
                            <span class="text-total">
                                Rp {{ number_format($totalPrice, 0, ',', '.') }}
                            </span>
                        </div>
                        <div class="col-md-6 text-md-right">
                            <span class="small-label d-block">Jumlah Item</span>
                            <span class="font-weight-bold">
                                {{ count($cart) }} item
                            </span>
                        </div>
                    </div>

                    <hr>

                    <form action="{{ route('kasir.transaksi.checkout') }}" method="POST">
                        @csrf

                        <div class="form-row">
                            <div class="form-group col-md-4">
                                <label class="small-label" for="payment_method">Metode Pembayaran</label>
                                <select name="payment_method" id="payment_method" class="form-control" required>
                                    <option value="" disabled selected>Pilih metode</option>
                                    <option value="cash">Cash</option>
                                    <option value="debit">Debit</option>
                                    <option value="credit">Credit</option>
                                    <option value="ewallet">E-Wallet</option>
                                </select>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="small-label" for="pay_amount">Uang Dibayar</label>
                                <input type="number"
                                       name="pay_amount"
                                       id="pay_amount"
                                       class="form-control"
                                       min="0"
                                       value="{{ old('pay_amount') }}"
                                       required>
                            </div>
                            <div class="form-group col-md-4">
                                <label class="small-label">Kembalian (perkiraan)</label>
                                <input type="text"
                                       id="change_preview"
                                       class="form-control"
                                       readonly
                                       value="Rp 0">
                            </div>
                        </div>

                        <div class="text-right">
                            <button type="submit"
                                    class="btn btn-main"
                                    @if($totalPrice <= 0) disabled @endif>
                                <i class="fas fa-check mr-1"></i> Proses Transaksi
                            </button>
                        </div>
                    </form>

                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-shield-alt mr-1"></i>
                        Setelah transaksi berhasil, sistem akan menyimpan data ke database dan menampilkan halaman struk.
                    </small>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- JS --}}
{{-- jQuery dari SB Admin 2 --}}
<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>

{{-- Bootstrap 4 JS --}}
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>

{{-- SB Admin 2 JS --}}
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

<script>
    // Jam realtime di header
    function updateClock() {
        const now = new Date();
        const options = { hour: '2-digit', minute: '2-digit', second: '2-digit' };
        document.getElementById('clock').textContent = now.toLocaleTimeString('id-ID', options);
    }
    setInterval(updateClock, 1000);
    updateClock();

    // Fokus otomatis ke input kode_barang
    document.getElementById('kode_barang').focus();

    // Hitung kembalian preview (di sisi frontend)
    const payInput = document.getElementById('pay_amount');
    const changePreview = document.getElementById('change_preview');
    const totalPrice = Number('{{ $totalPrice }}');

    function updateChangePreview() {
        const pay = parseInt(payInput.value || 0);
        let change = pay - totalPrice;
        if (isNaN(change) || change < 0) {
            change = 0;
        }

        changePreview.value = 'Rp ' + change.toLocaleString('id-ID');
    }

    if (payInput) {
        payInput.addEventListener('input', updateChangePreview);
    }
</script>

</body>
</html>
