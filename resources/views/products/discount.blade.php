@extends('layouts.app')
@section('page', 'Atur Diskon Produk')
@section('content')
    <div class="row mb-4">
        <div class="col-lg-8">
            <h5 class="mb-0">Atur Diskon untuk: <strong>{{ $product->name }}</strong></h5>
            <small class="text-muted">Kode: {{ $product->kode_barang }}</small>
        </div>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header bg-gradient-primary py-3">
            <h6 class="m-0 font-weight-bold text-white">
                <i class="fas fa-percent mr-2"></i>Form Pengaturan Diskon
            </h6>
        </div>
        <div class="card-body">
            <!-- Info Produk -->
            <div class="row mb-4">
                <div class="col-md-6">
                    <div class="card border-left-primary">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">
                                Harga Normal
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($product->price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                @if($product->discount_percent && $product->discount_percent > 0)
                <div class="col-md-6">
                    <div class="card border-left-success">
                        <div class="card-body">
                            <div class="text-xs font-weight-bold text-success text-uppercase mb-1">
                                Harga Dengan Diskon
                            </div>
                            <div class="h5 mb-0 font-weight-bold text-gray-800">
                                Rp {{ number_format($product->final_price, 0, ',', '.') }}
                            </div>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <!-- Form Diskon -->
            <form action="{{ route('products.updateDiscount', $product->id) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label for="discount_percent"><strong>Persentase Diskon (%)</strong> <span class="text-danger">*</span></label>
                    <input type="number" class="form-control @error('discount_percent') is-invalid @enderror" 
                           id="discount_percent" name="discount_percent" 
                           placeholder="0 - 100" 
                           value="{{ old('discount_percent', $product->discount_percent) }}" 
                           min="0" max="100" step="0.01">
                    <small class="form-text text-muted">
                        Masukkan angka antara 0-100. Contoh: 10 untuk 10% diskon. Kosongkan untuk menghapus diskon.
                    </small>
                    @error('discount_percent')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount_start_date"><strong>Tanggal Mulai Diskon</strong></label>
                            <input type="date" class="form-control @error('discount_start_date') is-invalid @enderror" 
                                   id="discount_start_date" name="discount_start_date" 
                                   value="{{ old('discount_start_date', $product->discount_start_date) }}">
                            <small class="form-text text-muted">
                                Wajib diisi jika ada persentase diskon
                            </small>
                            @error('discount_start_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="discount_end_date"><strong>Tanggal Akhir Diskon</strong></label>
                            <input type="date" class="form-control @error('discount_end_date') is-invalid @enderror" 
                                   id="discount_end_date" name="discount_end_date" 
                                   value="{{ old('discount_end_date', $product->discount_end_date) }}">
                            <small class="form-text text-muted">
                                Wajib diisi jika ada persentase diskon
                            </small>
                            @error('discount_end_date')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <!-- Info Alert -->
                @if($product->discount_percent && $product->discount_percent > 0)
                    <div class="alert alert-info mt-4">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Diskon Aktif:</strong> {{ $product->discount_percent }}% dari {{ $product->discount_start_date }} sampai {{ $product->discount_end_date }}
                    </div>
                @endif

                <!-- Buttons -->
                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save mr-2"></i>Simpan Diskon
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left mr-2"></i>Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Info Card -->
    <div class="card shadow">
        <div class="card-header bg-light py-3">
            <h6 class="m-0 font-weight-bold text-info">
                <i class="fas fa-lightbulb mr-2"></i>Informasi Tenggat Waktu Diskon
            </h6>
        </div>
        <div class="card-body">
            <ul class="mb-0">
                <li>Diskon hanya berlaku dalam rentang tanggal yang ditentukan</li>
                <li>Jika hari ini diluar periode diskon, pelanggan akan melihat harga normal</li>
                <li>Untuk menghapus diskon, kosongkan field persentase dan simpan</li>
                <li>Tanggal akhir diskon harus sama atau lebih besar dari tanggal mulai</li>
            </ul>
        </div>
    </div>

@endsection
