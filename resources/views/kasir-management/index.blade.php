@extends('layouts.app')
@section('page', 'Kasir Management')
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
    @endif

    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-lg-6">
            <h2 class="h4 text-gray-900 font-weight-bold mb-2">Kelola Kasir</h2>
            <p class="text-gray-600 mb-0">Manage semua akun kasir sistem kasir Anda di sini.</p>
        </div>
    </div>

    <!-- Kasir Table Card -->
    <div class="card shadow border-0">
        <div class="card-header bg-white py-4 border-bottom-subtle">
            <div class="row align-items-center">
                <div class="col-lg-12">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-users mr-2"></i>Daftar Kasir Terdaftar
                    </h6>
                    <small class="text-muted d-block mt-2">
                        Total: <strong>{{ $kasirs->total() }}</strong> kasir
                    </small>
                </div>
            </div>
        </div>

        <!-- Search Box -->
        <div class="card-body border-bottom">
            <form action="{{ route('kasir-management.index') }}" method="GET">
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
                            <a href="{{ route('kasir-management.index') }}" class="btn btn-secondary btn-sm">
                                <i class="fas fa-redo mr-1"></i> Reset
                            </a>
                        @endif
                        <a href="{{ route('kasir-management.create') }}" class="btn btn-success btn-sm shadow-sm float-right">
                            <i class="fas fa-plus mr-2"></i> Tambah Data
                        </a>
                    </div>
                </div>
            </form>
        </div>

        <div class="card-body p-0">
            @forelse ($kasirs as $kasir)
                @if ($loop->first)
                    <div class="table-responsive">
                        <table class="table table-sm table-borderless mb-0 align-middle">
                            <thead class="bg-light">
                                <tr>
                                    <th class="text-center text-muted font-weight-600 small py-2 px-3" style="width: 60px;">
                                        No
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Foto
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Nama
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Email
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        No Hp
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Alamat
                                    </th>
                                    <th class="text-uppercase text-muted font-weight-600 small py-2 px-3">
                                        Status
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
                                    {{-- No --}}
                                    <td class="text-center text-muted small py-2 px-3 align-middle">
                                        {{ ($kasirs->currentPage() - 1) * $kasirs->perPage() + $loop->iteration }}
                                    </td>

                                    {{-- Foto --}}
                                    <td class="py-2 px-3 align-middle">
                                        @if($kasir->foto && file_exists(public_path('storage/' . $kasir->foto)))
                                            <img src="{{ asset('storage/' . $kasir->foto) }}"
                                                alt="{{ $kasir->name }}"
                                                class="img-thumbnail"
                                                style="width: 40px; height: 40px; object-fit: cover;">
                                        @else
                                            <div class="bg-light d-inline-flex align-items-center justify-content-center"
                                                style="width: 40px; height: 40px; border: 1px solid #dee2e6; border-radius: 4px;">
                                                <i class="fas fa-user text-muted" style="font-size: 0.875rem;"></i>
                                            </div>
                                        @endif
                                    </td>

                                    {{-- Nama --}}
                                    <td class="py-2 px-3 align-middle">
                                        <span class="font-weight-500 text-gray-900">{{ $kasir->name }}</span>
                                    </td>

                                    {{-- Email --}}
                                    <td class="py-2 px-3 align-middle">
                                        <span class="text-muted small">{{ $kasir->email }}</span>
                                    </td>

                                    {{-- No Hp --}}
                                    <td class="py-2 px-3 align-middle">
                                        <span class="text-muted small">{{ $kasir->phone_number ?? '-' }}</span>
                                    </td>

                                    {{-- Alamat --}}
                                    <td class="py-2 px-3 align-middle">
                                        <span class="text-muted small" title="{{ $kasir->address }}">
                                            {{ \Illuminate\Support\Str::limit($kasir->address ?? '', 20) ?: '-' }}
                                        </span>
                                    </td>

                                    {{-- Status --}}
                                    <td class="py-2 px-3 align-middle">
                                        {{-- pakai isi button/status kamu yang lama di sini, cukup ganti class td-nya jadi py-2 px-3 --}}
                                        @if($kasir->is_active)
                                            <span class="badge badge-success">Aktif</span>
                                        @else
                                            <span class="badge badge-secondary">Nonaktif</span>
                                        @endif
                                    </td>

                                    {{-- Dibuat --}}
                                    <td class="py-2 px-3 align-middle">
                                        <span class="badge badge-light text-muted"
                                            style="background-color: #f8f9fa; border: 1px solid #dee2e6;">
                                            <i class="far fa-calendar mr-1"></i>{{ $kasir->created_at->format('d M Y') }}
                                        </span>
                                        <small class="d-block text-muted mt-1">
                                            {{ $kasir->created_at->format('H:i') }}
                                        </small>
                                    </td>

                                    {{-- Aksi --}}
                                    <td class="py-2 px-3 text-center align-middle">
                                        <div class="d-flex gap-2 justify-content-center">
                                            <a href="{{ route('kasir-management.edit', $kasir->id) }}"
                                            class="btn btn-sm p-2 text-primary text-decoration-none"
                                            title="Edit">
                                                <i class="fas fa-pencil-alt"></i>
                                            </a>
                                            <form action="{{ route('kasir-management.destroy', $kasir->id) }}"
                                                method="POST"
                                                class="d-inline m-0">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                        class="btn btn-sm p-2 text-danger text-decoration-none border-0 bg-transparent"
                                                        title="Delete"
                                                        onclick="return confirm('Yakin ingin menghapus kasir ini?')"
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
                {{-- bagian kosongmu yang lama biarkan saja seperti sebelumnya --}}
                {{-- ... --}}
            @endforelse
        </div>


        <!-- Pagination -->
        @if ($kasirs->hasPages())
            <div class="card-footer bg-white py-3 px-4 border-top-subtle">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $kasirs->firstItem() }}</strong> sampai <strong>{{ $kasirs->lastItem() }}</strong> dari <strong>{{ $kasirs->total() }}</strong> kasir
                        </small>
                    </div>
                    <div class="col-md-6">
                        <div class="text-right mt-3 mt-md-0">
                            {{ $kasirs->appends(request()->query())->links('pagination::bootstrap-4') }}
                        </div>
                    </div>
                </div>
            </div>
        @elseif ($kasirs->count() > 0)
            <div class="card-footer bg-light py-3 px-4 border-top-subtle">
                <small class="text-muted">
                    Menampilkan <strong>{{ $kasirs->count() }}</strong> kasir
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

        .foto-preview {
            transition: transform 0.2s ease;
        }

        .foto-preview:hover {
            transform: scale(1.1);
        }
    </style>

    <!-- Modal untuk Preview Foto -->
    <div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="fotoModalLabel">Preview Foto</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalFotoImg" src="" alt="Preview" class="img-fluid rounded" style="max-height: 500px; max-width: 100%;">
                    <p class="mt-3 text-muted mb-0">
                        <small id="modalNamaKasir"></small>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Handle foto preview modal
        const fotoElements = document.querySelectorAll('.foto-preview');
        fotoElements.forEach(element => {
            element.addEventListener('click', function() {
                const fotoSrc = this.getAttribute('data-foto');
                const nama = this.getAttribute('data-nama');
                document.getElementById('modalFotoImg').src = fotoSrc;
                document.getElementById('modalNamaKasir').textContent = 'Foto: ' + nama;
            });
        });

        // Handle toggle status
        const toggleStatusButtons = document.querySelectorAll('.toggle-status');
        toggleStatusButtons.forEach(button => {
            button.addEventListener('click', function() {
                const kasirId = this.getAttribute('data-id');
                const currentStatus = parseInt(this.getAttribute('data-status'));
                const newStatus = currentStatus === 1 ? 0 : 1;
                
                if (confirm('Ubah status kasir ini?')) {
                    fetch(`/admin/kasir-management/${kasirId}/toggle-status`, {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                            'Content-Type': 'application/json'
                        },
                        body: JSON.stringify({ is_active: newStatus })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            // Update button appearance
                            const badge = this.querySelector('.badge');
                            if (newStatus === 1) {
                                badge.textContent = 'Aktif';
                                badge.classList.remove('badge-danger');
                                badge.classList.add('badge-success');
                            } else {
                                badge.textContent = 'Nonaktif';
                                badge.classList.remove('badge-success');
                                badge.classList.add('badge-danger');
                            }
                            this.setAttribute('data-status', newStatus);
                            
                            // Show success message
                            showAlert('Status berhasil diubah!', 'success');
                        } else {
                            showAlert('Gagal mengubah status!', 'danger');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        showAlert('Terjadi kesalahan!', 'danger');
                    });
                }
            });
        });

        // Helper function to show alert
        function showAlert(message, type) {
            const alertHtml = `
                <div class="alert alert-${type} alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="fas fa-${type === 'success' ? 'check-circle' : 'exclamation-circle'} mr-2"></i>
                        <span>${message}</span>
                    </div>
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            `;
            
            const alertContainer = document.querySelector('.card-header').parentElement;
            const tempDiv = document.createElement('div');
            tempDiv.innerHTML = alertHtml;
            alertContainer.insertBefore(tempDiv.firstElementChild, alertContainer.firstChild);
            
            // Auto-dismiss after 3 seconds
            setTimeout(() => {
                const alert = alertContainer.querySelector('.alert');
                if (alert) {
                    alert.remove();
                }
            }, 3000);
        }
    </script>
@endsection