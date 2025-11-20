@extends('layouts.app')
@section('page', 'Tambah Kasir')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <p class="mb-0">Tambahkan kasir baru ke sistem kasir.</p>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Tambah Kasir</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('kasir-management.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="name">Nama <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama kasir" value="{{ old('name') }}" required>
                            @error('name')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="email">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" placeholder="Masukkan email kasir" value="{{ old('email') }}" required>
                            @error('email')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="phone_number">No Hp <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('phone_number') is-invalid @enderror" id="phone_number" name="phone_number" placeholder="Contoh: 08xxxxxxxxxx" value="{{ old('phone_number') }}" required>
                            @error('phone_number')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="password">Password <span class="text-danger">*</span></label>
                            <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password" placeholder="Masukkan password" required>
                            @error('password')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="address">Alamat <span class="text-danger">*</span></label>
                            <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" placeholder="Masukkan alamat kasir" value="{{ old('address') }}" required>
                            @error('address')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="foto">Foto Profil</label>
                            <div id="fotoPreview" style="display: none; margin-bottom: 1rem;">
                                <img id="previewImg" src="" alt="Preview" class="img-thumbnail rounded" style="max-width: 150px; max-height: 150px; object-fit: cover;">
                                <p class="small text-muted mt-2">Preview foto</p>
                            </div>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input @error('foto') is-invalid @enderror" id="foto" name="foto" accept="image/*">
                                <label class="custom-file-label" for="foto">Pilih file...</label>
                            </div>
                            <small class="form-text text-muted">Format: JPG, PNG. Ukuran maksimal: 2MB</small>
                            @error('foto')
                                <span class="invalid-feedback d-block">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="is_active">Status <span class="text-danger">*</span></label>
                            <select class="form-control @error('is_active') is-invalid @enderror" id="is_active" name="is_active" required>
                                <option value="1" {{ old('is_active', '1') == '1' ? 'selected' : '' }}>Aktif</option>
                                <option value="0" {{ old('is_active', '1') == '0' ? 'selected' : '' }}>Nonaktif</option>
                            </select>
                            @error('is_active')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group mt-4">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm"></i> Simpan
                    </button>
                    <a href="{{ route('kasir-management.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times fa-sm"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <style>
        .custom-file-label::after {
            content: "Browse";
        }

        .form-group label {
            font-weight: 500;
            color: #2e59d9;
            margin-bottom: 0.5rem;
        }

        .form-control, .custom-file-input {
            border-radius: 0.35rem;
        }

        .form-control:focus, .custom-file-input:focus {
            border-color: #80bdff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
        }

        .invalid-feedback {
            display: block;
            color: #e74a3b;
        }

        .text-danger {
            color: #e74a3b;
            font-weight: 600;
        }
    </style>

    <script>
        document.getElementById('foto').addEventListener('change', function(e) {
            const file = e.target.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(event) {
                    const previewImg = document.getElementById('previewImg');
                    const fotoPreview = document.getElementById('fotoPreview');
                    previewImg.src = event.target.result;
                    fotoPreview.style.display = 'block';
                    
                    // Update label dengan nama file
                    const label = document.querySelector('.custom-file-label');
                    label.textContent = file.name;
                };
                reader.readAsDataURL(file);
            }
        });
    </script>
@endsection
