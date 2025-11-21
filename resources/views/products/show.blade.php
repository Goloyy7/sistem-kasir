@extends('layouts.app')
@section('page', 'Detail Produk')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <p class="mb-0">Lihat detail informasi produk.</p>
    </div>

    <!-- Detail Card -->
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">Detail Produk</h6>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-5 text-center mb-4 mb-md-0">
                            @if($product->image)
                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-fluid rounded" style="max-width: 200px; max-height: 200px; object-fit: cover; border: 2px solid #dee2e6;">
                            @else
                                <div
                                    class="bg-light d-inline-flex align-items-center justify-content-center rounded"
                                    style="width: 200px; height: 200px; border: 2px solid #dee2e6;"
                                >
                                    <div class="text-center">
                                        <i class="fas fa-image fa-5x text-muted mb-3"></i>
                                        <p class="text-muted mb-0">Tidak ada gambar</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-7">
                            <div class="mb-3">
                                <label class="font-weight-bold text-primary mb-1">Nama Produk</label>
                                <p class="text-gray-900 mb-0">{{ $product->name }}</p>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-primary mb-1">Kategori</label>
                                <p class="mb-0">
                                    <span class="badge badge-info">{{ $product->category->name }}</span>
                                </p>
                            </div>

                            <div class="mb-3">
                                <label class="font-weight-bold text-primary mb-1">Harga</label>
                                <p class="text-gray-900 mb-0 font-weight-bold text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>

                            <div class="mb-0">
                                <label class="font-weight-bold text-primary mb-1">Stok</label>
                                <p class="mb-0">
                                    @if($product->stock > 0)
                                        <span class="badge badge-success">{{ $product->stock }} unit</span>
                                    @else
                                        <span class="badge badge-danger">Habis</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    @if($product->description)
                        <div class="mb-3">
                            <label class="font-weight-bold text-primary mb-1">Deskripsi</label>
                            <p class="text-gray-900 mb-0">{{ $product->description }}</p>
                        </div>

                        <hr class="my-4">
                    @endif

                    <div class="row">
                        <div class="col-md-6">
                            <label class="font-weight-bold text-primary mb-1">Dibuat Pada</label>
                            <p class="text-gray-900 mb-0">
                                {{ $product->created_at->format('d M Y H:i') }}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <label class="font-weight-bold text-primary mb-1">Diperbarui Pada</label>
                            <p class="text-gray-900 mb-0">
                                {{ $product->updated_at->format('d M Y H:i') }}
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
                        <a href="{{ route('products.edit', $product->id) }}" class="btn btn-primary btn-block">
                            <i class="fas fa-edit mr-2"></i> Edit Produk
                        </a>
                        <a href="{{ route('products.index') }}" class="btn btn-secondary btn-block">
                            <i class="fas fa-arrow-left mr-2"></i> Kembali
                        </a>
                        <button type="button" class="btn btn-danger btn-block" data-toggle="modal" data-target="#deleteModal">
                            <i class="fas fa-trash mr-2"></i> Hapus Produk
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
                            <strong>ID:</strong> {{ $product->id }}
                        </p>
                        <p class="mb-2">
                            <strong>Kode Barang ID:</strong> {{ $product->category_id }}
                        </p>
                        <p class="mb-0">
                            <strong>Status Stok:</strong> 
                            @if($product->stock > 0)
                                <span class="badge badge-success">Tersedia</span>
                            @else
                                <span class="badge badge-danger">Habis</span>
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
                    <p>Anda yakin ingin menghapus produk <strong>{{ $product->name }}</strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan!</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="d-inline">
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
