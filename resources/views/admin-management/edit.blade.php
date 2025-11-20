@extends('layouts.app')
@section('page', 'Edit Admin')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <p class="mb-0">Edit data admin di sistem kasir.</p>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Admin</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('admin-management.update', $admin->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="name">Nama <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama admin" value="{{ old('name', $admin->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email <span class="text-danger">*</span></label>
                    <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Masukkan email admin" value="{{ old('email', $admin->email) }}" required>
                    @error('email')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password">Password <span class="text-muted">(Biarkan kosong jika tidak ingin mengubah)</span></label>
                    <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password baru (opsional)">
                    @error('password')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="password_confirmation">Konfirmasi Password</label>
                    <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror" id="password_confirmation" name="password_confirmation" placeholder="Konfirmasi password baru (opsional)">
                    @error('password_confirmation')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('admin-management.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times fa-sm"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
@endsection
