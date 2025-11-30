@extends('layouts.app')
@section('page', 'Manajemen Produk')
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

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <h2 class="h4 text-gray-900 font-weight-bold mb-2">Kelola Produk</h2>
            <p class="text-gray-600 mb-0">Manage semua produk sistem kasir Anda di sini.</p>
        </div>
    </div>

    <!-- Products Table Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-3 border-bottom-subtle">
            <div class="row align-items-center">
                <div class="col-lg-8">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-box mr-2"></i>Daftar Produk Terdaftar
                    </h6>
                    <small class="text-muted d-block mt-2">
                        Total: <strong>{{ $products->total() }}</strong> produk
                    </small>
                </div>
                <div class="col-lg-4 d-flex justify-content-lg-end mt-3 mt-lg-0">
                    <a href="{{ route('products.create') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-plus mr-2"></i> Tambah Data
                    </a>
                </div>
            </div>
        </div>

        <!-- Search & Filter Section -->
        <div class="card-body border-bottom py-3">
            <form action="{{ route('products.index') }}" method="GET">
                <div class="row g-2 align-items-end">
                    {{-- Search Box --}}
                    <div class="col-lg-4">
                        <label for="search" class="form-label small text-muted mb-1">
                            <i class="fas fa-search mr-1"></i>Kode / Nama Produk
                        </label>
                        <input type="text"
                            name="search"
                            id="search"
                            class="form-control form-control-sm"
                            placeholder="Cari kode atau nama..."
                            value="{{ request('search') }}">
                    </div>

                    {{-- Filter Kategori --}}
                    <div class="col-lg-3">
                        <label for="category_id" class="form-label small text-muted mb-1">
                            <i class="fas fa-tag mr-1"></i>Kategori
                        </label>
                        <select name="category_id" id="category_id" class="form-control form-control-sm">
                            <option value="">Semua</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    {{-- Filter Stok --}}
                    <div class="col-lg-2">
                        <label for="stock_filter" class="form-label small text-muted mb-1">
                            <i class="fas fa-box mr-1"></i>Stok
                        </label>
                        <select name="stock_filter" id="stock_filter" class="form-control form-control-sm">
                            <option value="">Semua</option>
                            <option value="available" {{ request('stock_filter') === 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="habis" {{ request('stock_filter') === 'habis' ? 'selected' : '' }}>Habis</option>
                        </select>
                    </div>

                    {{-- Tombol Action --}}
                    <div class="col-lg-3 d-flex gap-2">
                        @if(request()->hasAny(['search','category_id','stock_filter']))
                            <a href="{{ route('products.index') }}"
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

                {{-- Active Filter Badges --}}
                @if(request()->hasAny(['search','category_id','stock_filter']))
                    <div class="mt-3 d-flex flex-wrap gap-2">
                        @if(request('search'))
                            <span class="badge badge-primary">
                                <i class="fas fa-search mr-1"></i> "{{ request('search') }}"
                                <a href="{{ route('products.index', ['category_id' => request('category_id'), 'stock_filter' => request('stock_filter')]) }}" 
                                   class="text-white ml-2" style="text-decoration: none; cursor: pointer;">×</a>
                            </span>
                        @endif
                        
                        @if(request('category_id'))
                            @php
                                $selectedCategory = $categories->firstWhere('id', request('category_id'));
                            @endphp
                            <span class="badge badge-info">
                                <i class="fas fa-tag mr-1"></i> {{ $selectedCategory->name ?? 'Unknown' }}
                                <a href="{{ route('products.index', ['search' => request('search'), 'stock_filter' => request('stock_filter')]) }}" 
                                   class="text-white ml-2" style="text-decoration: none; cursor: pointer;">×</a>
                            </span>
                        @endif

                        @if(request('stock_filter'))
                            <span class="badge badge-warning">
                                <i class="fas fa-box mr-1"></i> {{ request('stock_filter') === 'available' ? 'Tersedia' : 'Habis' }}
                                <a href="{{ route('products.index', ['search' => request('search'), 'category_id' => request('category_id')]) }}" 
                                   class="text-white ml-2" style="text-decoration: none; cursor: pointer;">×</a>
                            </span>
                        @endif
                    </div>
                @endif
            </form>
        </div>

        <div class="card-body p-0">
            @forelse ($products as $product)
                @if ($loop->first)
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center text-uppercase text-muted font-weight-600 small py-2 px-3" style="width: 60px;">
                                        No
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3" style="width: 60px;">
                                        Gambar
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Kode Produk
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Nama Produk
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Kategori
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Harga
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Stok
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Dibuat
                                    </th>
                                    <th class="text-center text-uppercase text-muted font-weight-600 small py-2 px-3" style="width: 140px;">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                @endif
                                <tr class="border-bottom">
                                    <td class="text-center text-muted small py-2 px-3 align-middle">
                                        {{ ($products->currentPage() - 1) * $products->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        @if($product->image && file_exists(public_path('storage/' . $product->image)))
                                            <img src="{{ asset('storage/' . $product->image) }}"
                                                alt="{{ $product->name }}"
                                                class="rounded product-image"
                                                style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;"
                                                data-toggle="modal"
                                                data-target="#imageModal"
                                                data-image="{{ asset('storage/' . $product->image) }}"
                                                data-nama="{{ $product->name }}">
                                        @else
                                            <div class="bg-light d-inline-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; border: 1px solid #dee2e6; border-radius: 4px;">
                                                <i class="fas fa-image text-muted" style="font-size: 0.875rem;"></i>
                                            </div>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="font-weight-500 text-gray-900">{{ $product->kode_barang }}</span>
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="font-weight-500 text-gray-900">{{ $product->name }}</span>
                                        @if($product->description)
                                            <small class="d-block text-muted" title="{{ $product->description }}">
                                                {{ \Illuminate\Support\Str::limit($product->description, 30) }}
                                            </small>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="badge badge-info">{{ $product->category->name }}</span>
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="font-weight-500 text-success">
                                            Rp {{ number_format($product->price, 0, ',', '.') }}
                                        </span>
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        @if($product->stock > 0)
                                            <span class="badge badge-success">{{ $product->stock }} unit</span>
                                        @else
                                            <span class="badge badge-danger">Habis</span>
                                        @endif
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="badge badge-light text-muted"
                                            style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                            <i class="far fa-calendar mr-1"></i>{{ $product->created_at->format('d M Y') }}
                                        </span>
                                        <small class="d-block text-muted mt-1">
                                            {{ $product->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="py-2 px-2 text-center align-middle">
                                        <div class="d-flex gap-2 justify-content-center flex-wrap">
                                            <a href="{{ route('products.show', $product->id) }}"
                                            class="btn btn-sm p-2 text-info text-decoration-none"
                                            title="Lihat Detail">
                                                <i class="fas fa-search"></i>
                                            </a>
                                            <a href="{{ route('products.edit', $product->id) }}"
                                            class="btn btn-sm p-2 text-primary text-decoration-none"
                                            title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('products.destroy', $product->id) }}"
                                                method="POST"
                                                class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm p-2 text-danger text-decoration-none border-0 bg-transparent"
                                                        title="Delete"
                                                        onclick="return confirm('Yakin ingin menghapus produk ini?')"
                                                        style="cursor: pointer;">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                @if ($loop->last)
                            </tbody>
                        </table>
                    </div>
                @endif
            @empty
                <div class="text-center py-5 px-4">
                    <div class="mb-3">
                        <i class="fas fa-box-open fa-3x text-muted"></i>
                    </div>
                    @if(request('search') || request('category_id') || request('stock_filter'))
                        <h5 class="text-gray-600 mb-1">Tidak Ada Hasil</h5>
                        <p class="text-muted mb-3">Tidak ditemukan produk dengan kriteria pencarian Anda.</p>
                        <a href="{{ route('products.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-redo mr-1"></i> Kembali ke Semua Produk
                        </a>
                    @else
                        <h5 class="text-gray-600 mb-1">Belum Ada Data Produk</h5>
                        <p class="text-muted mb-3">Mulai tambahkan produk baru untuk sistem kasir Anda.</p>
                        <a href="{{ route('products.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus mr-1"></i> Tambah Produk Pertama
                        </a>
                    @endif
                </div>
            @endforelse
        </div>


        <!-- Pagination -->
        @if ($products->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top-subtle">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $products->firstItem() }}</strong> sampai <strong>{{ $products->lastItem() }}</strong> dari <strong>{{ $products->total() }}</strong> produk
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="text-right mt-3 mt-md-0">
                            {{ $products->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($products->count() > 0)
            <div class="card-footer bg-light py-3 px-4 border-top-subtle">
                <small class="text-muted">
                    Menampilkan <strong>{{ $products->count() }}</strong> produk
                </small>
            </div>
        @endif
    </div>

    <!-- Inline Styles -->
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
            font-weight: 600;
            letter-spacing: 0.3px;
            padding-top: 0.75rem;
            padding-bottom: 0.75rem;
        }

        .table td {
            padding: 0.75rem;
            vertical-align: middle;
        }

        .table-borderless td {
            border-color: transparent;
        }

        .btn-link {
            text-decoration: none;
            font-size: 0.95rem;
            transition: color 0.2s ease;
        }

        .btn-link:hover {
            text-decoration: none;
            opacity: 0.7;
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

        .input-group-text {
            border: 1px solid #ced4da;
        }

        .pagination {
            margin-bottom: 0;
        }

        .product-image {
            transition: transform 0.2s ease;
        }

        .product-image:hover {
            transform: scale(1.1);
        }

        .form-control-sm {
            font-size: 0.875rem;
        }

        .badge a {
            cursor: pointer;
        }

        .badge a:hover {
            opacity: 0.8;
        }
    </style>

    <!-- Modal untuk Preview Gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" role="dialog" aria-labelledby="imageModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Preview Gambar</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImageImg" src="" alt="Preview" class="img-fluid" style="max-height: 500px; max-width: 100%;">
                    <p class="mt-3 text-muted mb-0">
                        <small id="modalNamaProduct"></small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle image preview modal
        const imageElements = document.querySelectorAll('.product-image');
        imageElements.forEach(element => {
            element.addEventListener('click', function() {
                const imageSrc = this.getAttribute('data-image');
                const nama = this.getAttribute('data-nama');
                document.getElementById('modalImageImg').src = imageSrc;
                document.getElementById('modalNamaProduct').textContent = 'Produk: ' + nama;
            });
        });
    </script>
@endsection