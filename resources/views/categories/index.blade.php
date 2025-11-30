@extends('layouts.app')
@section('page', 'Manajemen Kategori')
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
            <h2 class="h4 text-gray-900 font-weight-bold mb-2">Kelola Kategori</h2>
            <p class="text-gray-600 mb-0">Manage semua kategori produk sistem kasir Anda di sini.</p>
        </div>
    </div>

    <!-- Categories Table Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-4 border-bottom-subtle">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-list mr-2"></i>Daftar Kategori Terdaftar
                    </h6>
                    <small class="text-muted d-block mt-2">
                        Total: <strong>{{ $categories->total() }}</strong> kategori
                    </small>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="card-body border-bottom">
            <form action="{{ route('categories.index') }}" method="GET">

                <div class="row">
                    <div class="col-md-8">

                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Cari berdasarkan nama kategori..." 
                                   value="{{ request('search') }}">
                            <div class="input-group-append">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-search"></i>
                                </button>
                                @if(request('search'))
                                    <a href="{{ route('categories.index')}}" 
                                       class="btn btn-secondary">
                                        <i class="fas fa-times"></i>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mt-2 mt-md-0">
                        
                        <a href="{{ route('categories.create') }}" class="btn btn-success btn-sm shadow-sm float-right">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            @forelse ($categories as $category)
                @if ($loop->first)
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center text-muted font-weight-600 small py-2 px-3" style="width: 60px;">
                                        No
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Nama Kategori
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Dibuat
                                    </th>
                                    <th class="text-center text-muted font-weight-600 small py-2 px-3" style="width: 120px;">
                                        Aksi
                                    </th>
                                </tr>
                            </thead>
                            <tbody>
                @endif
                                <tr class="border-bottom">
                                    <td class="text-center text-muted small py-2 px-3 align-middle">
                                        {{ ($categories->currentPage() - 1) * $categories->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="font-weight-500 text-gray-900">{{ $category->name }}</span>
                                    </td>
                                    <td class="py-2 px-3 align-middle">
                                        <span class="badge badge-light text-muted"
                                            style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                            <i class="far fa-calendar mr-1"></i>{{ $category->created_at->format('d M Y') }}
                                        </span>
                                        <small class="d-block text-muted mt-1">
                                            {{ $category->created_at->format('H:i') }}
                                        </small>
                                    </td>
                                    <td class="py-2 px-3 text-center align-middle">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('categories.edit', $category->id) }}"
                                            class="btn btn-sm p-2 text-primary text-decoration-none"
                                            title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('categories.destroy', $category->id) }}"
                                                method="POST"
                                                class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm p-2 text-danger text-decoration-none border-0 bg-transparent"
                                                        title="Delete"
                                                        onclick="return confirm('Yakin ingin menghapus kategori ini?')"
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
                        <i class="fas fa-layer-group fa-3x text-muted"></i>
                    </div>
                    @if(request('search'))
                        <h5 class="text-gray-600 mb-1">Tidak Ada Hasil</h5>
                        <p class="text-muted mb-3">Tidak ditemukan kategori dengan kata kunci "{{ request('search') }}"</p>
                        <a href="{{ route('categories.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-redo mr-1"></i> Kembali ke Semua Kategori
                        </a>
                    @else
                        <h5 class="text-gray-600 mb-1">Belum Ada Data Kategori</h5>
                        <p class="text-muted mb-3">Mulai tambahkan kategori baru untuk sistem kasir Anda.</p>
                        <a href="{{ route('categories.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus mr-1"></i> Tambah Kategori Pertama
                        </a>
                    @endif
                </div>
            @endforelse
        </div>


        <!-- Pagination -->
        @if ($categories->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top-subtle">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $categories->firstItem() }}</strong> sampai <strong>{{ $categories->lastItem() }}</strong> dari <strong>{{ $categories->total() }}</strong> kategori
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="text-right mt-3 mt-md-0">
                            {{ $categories->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($categories->count() > 0)
            <div class="card-footer bg-light py-3 px-4 border-top-subtle">
                <small class="text-muted">
                    Menampilkan <strong>{{ $categories->count() }}</strong> kategori
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
    </style>
@endsection
