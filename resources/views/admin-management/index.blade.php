@extends('layouts.app')
@section('page', 'Admin Management')
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
            <h2 class="h4 text-gray-900 font-weight-bold mb-2">Kelola Admin</h2>
            <p class="text-gray-600 mb-0">Manage semua akun admin sistem kasir Anda di sini.</p>
        </div>
    </div>

    <!-- Admin Table Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-4 border-bottom-subtle">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users mr-2"></i>Daftar Admin Terdaftar
                    </h6>
                    <small class="text-muted d-block mt-2">
                        Total: <strong>{{ $admins->total() }}</strong> admin
                    </small>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="card-body border-bottom">
            <form action="{{ route('admin-management.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-8">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text bg-white border-right-0">
                                    <i class="fas fa-search text-muted"></i>
                                </span>
                            </div>
                            <input type="text" name="search" class="form-control border-left-0 pl-0" 
                                   placeholder="Cari berdasarkan nama atau email..." 
                                   value="{{ request('search') }}">
                        </div>
                    </div>
                    <div class="col-md-4 mt-2 mt-md-0">
                        <button type="submit" class="btn btn-primary btn-sm mr-2">
                            <i class="fas fa-search mr-1"></i> Cari
                        </button>
                        @if(request('search'))
                            <a href="{{ route('admin-management.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-redo mr-1"></i> Reset
                            </a>
                        @endif
                        <a href="{{ route('admin-management.create') }}" class="btn btn-success btn-sm shadow-sm float-right">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            @forelse ($admins as $admin)
                @if ($loop->first)
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center text-uppercase text-muted font-weight-600 small py-3 pl-4" style="width: 60px;">No</th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-3">Nama</th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-3">Email</th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-3">Dibuat</th>
                                    <th class="text-center text-uppercase text-muted font-weight-600 small py-3" style="width: 120px;">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                @endif
                                <tr class="border-bottom">
                                    <td class="text-center text-muted small py-3 align-middle pl-4">
                                        {{ ($admins->currentPage() - 1) * $admins->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="py-3 align-middle">
                                        <span class="font-weight-500 text-gray-900">{{ $admin->name }}</span>
                                    </td>
                                    <td class="py-3 align-middle">
                                        <span class="text-muted small">{{ $admin->email }}</span>
                                    </td>
                                    <td class="py-3 align-middle">
                                        <span class="badge badge-light text-muted" style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                            <i class="far fa-calendar mr-1"></i>{{ $admin->created_at->format('d M Y') }}
                                        </span>
                                        <small class="d-block text-muted mt-1">{{ $admin->created_at->format('H:i') }}</small>
                                    </td>
                                    <td class="py-3 text-center align-middle">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('admin-management.edit', $admin->id) }}" class="btn btn-sm p-2 text-primary text-decoration-none" title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('admin-management.destroy', $admin->id) }}" method="POST" class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="btn btn-sm p-2 text-danger text-decoration-none border-0 bg-transparent" title="Delete" onclick="return confirm('Yakin ingin menghapus admin ini?')" style="cursor: pointer;">
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
                        <i class="fas fa-inbox fa-3x text-muted"></i>
                    </div>
                    @if(request('search'))
                        <h5 class="text-gray-600 mb-1">Tidak Ada Hasil</h5>
                        <p class="text-muted mb-3">Tidak ditemukan admin dengan kata kunci "{{ request('search') }}"</p>
                        <a href="{{ route('admin-management.index') }}" class="btn btn-sm btn-secondary">
                            <i class="fas fa-redo mr-1"></i> Kembali ke Semua Admin
                        </a>
                    @else
                        <h5 class="text-gray-600 mb-1">Belum Ada Data Admin</h5>
                        <p class="text-muted mb-3">Mulai tambahkan admin baru untuk mengelola sistem kasir Anda.</p>
                        <a href="{{ route('admin-management.create') }}" class="btn btn-sm btn-success">
                            <i class="fas fa-plus mr-1"></i> Tambah Admin Pertama
                        </a>
                    @endif
                </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if ($admins->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top-subtle">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $admins->firstItem() }}</strong> sampai <strong>{{ $admins->lastItem() }}</strong> dari <strong>{{ $admins->total() }}</strong> admin
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="text-right mt-3 mt-md-0">
                            {{ $admins->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($admins->count() > 0)
            <div class="card-footer bg-light py-3 px-4 border-top-subtle">
                <small class="text-muted">
                    Menampilkan <strong>{{ $admins->count() }}</strong> admin
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