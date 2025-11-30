<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Kasir - Beranda</title>

    <link href="{{ asset('vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet" type="text/css">
    <link href="{{ asset('css/sb-admin-2.min.css') }}" rel="stylesheet">

    <style>
        body {
            background: #f8f9fc;
        }

        .landing-wrapper {
            min-height: 100vh;
        }

        .card-landing:hover {
            transform: translateY(-2px);
            transition: transform 0.15s ease, box-shadow 0.15s ease;
            box-shadow: 0 0.5rem 1rem rgba(0,0,0,0.08) !important;
        }

        .badge-env {
            font-size: 0.7rem;
            letter-spacing: .08em;
            text-transform: uppercase;
        }
    </style>
</head>
<body>

<div class="container landing-wrapper d-flex align-items-center justify-content-center">
    <div class="row w-100 justify-content-center">
        <div class="col-xl-10 col-lg-11">

            <!-- Header -->
            <div class="text-center mb-4">
                <span class="badge badge-primary badge-env mb-2">
                    Project Ujikom · Point Of Sales
                </span>
                <h1 class="h3 text-gray-900 font-weight-bold mb-1">
                    Sistem Kasir
                </h1>
                <p class="text-muted mb-1">
                    SMKN 1 Garut &mdash; Pengembangan Perangkat Lunak dan Gim
                </p>
                <p class="text-gray-600 mb-0">
                    Silakan pilih peran untuk masuk ke sistem. Admin mengelola data,
                    kasir melakukan transaksi penjualan.
                </p>
            </div>

            <!-- Card -->
            <div class="row">
                <!-- Card Admin -->
                <div class="col-md-6 mb-3">
                    <div class="card card-landing border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                     style="width: 44px; height: 44px; background-color: #e3f2fd;">
                                    <i class="fas fa-user-shield text-primary"></i>
                                </div>
                                <div class="text-left">
                                    <h5 class="mb-0 text-gray-900 font-weight-bold">Login Admin</h5>
                                    <small class="text-muted">
                                        Kelola produk, kasir, dan pantau transaksi.
                                    </small>
                                </div>
                            </div>

                            <ul class="list-unstyled small text-muted mb-3">
                                <li><i class="fas fa-check text-success mr-1"></i> Manajemen produk & kategori</li>
                                <li><i class="fas fa-check text-success mr-1"></i> Manajemen akun kasir (aktif / nonaktif)</li>
                                <li><i class="fas fa-check text-success mr-1"></i> Monitoring transaksi & unduh struk PDF</li>
                            </ul>

                            <div class="mt-auto">
                                <a href="{{ url('admin/login') }}" class="btn btn-primary btn-block">
                                    Masuk sebagai Admin
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Card Kasir -->
                <div class="col-md-6 mb-3">
                    <div class="card card-landing border-0 shadow-sm h-100">
                        <div class="card-body d-flex flex-column">
                            <div class="d-flex align-items-center mb-3">
                                <div class="rounded-circle d-flex align-items-center justify-content-center mr-3"
                                     style="width: 44px; height: 44px; background-color: #e8f5e9;">
                                    <i class="fas fa-cash-register text-success"></i>
                                </div>
                                <div class="text-left">
                                    <h5 class="mb-0 text-gray-900 font-weight-bold">Login Kasir</h5>
                                    <small class="text-muted">
                                        Melakukan transaksi dan mencetak struk.
                                    </small>
                                </div>
                            </div>

                            <ul class="list-unstyled small text-muted mb-3">
                                <li><i class="fas fa-check text-success mr-1"></i> Input barang dengan kode atau dropdown</li>
                                <li><i class="fas fa-check text-success mr-1"></i> Hitung total & kembalian otomatis</li>
                                <li><i class="fas fa-check text-success mr-1"></i> Cetak struk 58mm atau simpan sebagai PDF</li>
                            </ul>

                            <div class="mt-auto">
                                <a href="{{ url('kasir/login') }}" class="btn btn-outline-success btn-block">
                                    Masuk sebagai Kasir
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="text-center mt-3">
                <small class="text-muted">
                    Dibuat oleh Indra Goldy dengan Laravel & Bootstrap 4 · Tema SB Admin 2
                </small>
            </div>
        </div>
    </div>
</div>

<script src="{{ asset('vendor/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-easing/jquery.easing.min.js') }}"></script>
<script src="{{ asset('js/sb-admin-2.min.js') }}"></script>

</body>
</html>
