@extends('layouts.app')
@section('page', 'Edit Kategori')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <p class="mb-0">Edit data kategori di sistem kasir.</p>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Kategori</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('categories.update', $category->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nama Kategori <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama kategori" value="{{ old('name', $category->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('categories.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times fa-sm"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
