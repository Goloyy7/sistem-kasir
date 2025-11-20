@extends('layouts.app')
@section('page', 'Detail Kasir')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <p class="mb-0">Lihat detail informasi kasir.</p>
    </div>

    <!-- Detail Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Kasir</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            @if($user->foto)
                                <img src="{{ asset('storage/' . $user->foto) }}" alt="{{ $user->name }}" class="img-fluid rounded" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 2px solid #dee2e6;">
                            @else
                                <div
                                    class="bg-secondary d-inline-flex align-items-center justify-content-center rounded-circle"
                                    style="width: 90px; height: 90px;"
                                >
                                    <i class="fas fa-user text-white" style="font-size: 2.2rem;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="font-weight-bold text-primary mb-1">Nama</label>
                                <p class="text-gray-900 mb-0">{{ $user->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-primary mb-1">Email</label>
                                <p class="text-gray-900 mb-0">{{ $user->email }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-primary mb-1">No Hp</label>
                                <p class="text-gray-900 mb-0">{{ $user->phone_number ?? '-' }}</p>
                            </div>

                            <div class="mb-0">
                                <label class="font-weight-bold text-primary mb-1">Status</label>
                                <p class="mb-0">
                                    @if($user->is_active)
                                        <span class="badge badge-success">Aktif</span>
                                    @else
                                        <span class="badge badge-danger">Nonaktif</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    <div class="mb-3">
                        <label class="font-weight-bold text-primary mb-1">Alamat</label>
                        <p class="text-gray-900 mb-0">{{ $user->address ?? '-' }}</p>
                    </div>

                    <hr class="my-4">

                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-weight-bold text-primary mb-1">Dibuat Pada</label>
                            <p class="text-gray-900 mb-0">
                                {{ $user->created_at ? $user->created_at->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold text-primary mb-1">Diperbarui Pada</label>
                            <p class="text-gray-900 mb-0">
                                {{ $user->updated_at ? $user->updated_at->format('d M Y H:i') : '-' }}
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Action Card -->
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Aksi</h6>
                </div>
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <a href="{{ route('kasir-management.edit', $user->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit mr-2"></i> Edit Kasir
                        </a>
                        <a href="{{ route('kasir-management.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash mr-2"></i> Hapus Kasir
                        </button>
                    </div>
                </div>
            </div>

            <!-- Info Card -->
            <div class="card shadow">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Informasi Sistem</h6>
                </div>
                <div class="card-body">
                    <small class="text-muted">
                        <p class="mb-2">
                            <strong>ID:</strong> {{ $user->id }}
                        </p>
                        <p class="mb-2">
                            <strong>Role:</strong> Kasir
                        </p>
                        <p class="mb-0">
                            <strong>Status:</strong> 
                            @if($user->is_active)
                                <span class="badge badge-success">Aktif</span>
                            @else
                                <span class="badge badge-danger">Nonaktif</span>
                            @endif
                        </p>
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Penghapusan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <p>Anda yakin ingin menghapus kasir <strong>{{ $user->name }}</strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('kasir-management.destroy', $user->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        .badge {
            padding: 0.35rem 0.65rem;
        }

        .card {
            border: none;
        }

        .card-header {
            background-color: #f8f9fa;
            border-bottom: 1px solid #dee2e6;
        }

        .btn-block {
            display: block;
            width: 100%;
        }

        .d-grid {
            display: grid;
            gap: 0.5rem;
        }

        label {
            margin-bottom: 0.25rem;
        }

        p {
            margin-bottom: 0;
        }
    </style>
@endsection
