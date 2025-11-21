@extends('layouts.app')
@section('page', 'Edit Produk')
@section('content')
    <div class="d-sm-flex align-items-center justify-content-between mb-4">
        <p class="mb-0">Edit data produk di sistem kasir.</p>
    </div>

    <!-- Form Card -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 font-weight-bold text-primary">Form Edit Produk</h6>
        </div>
        <div class="card-body">
            <form action="{{ route('products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="category_id">Kategori <span class="text-danger">*</span></label>
                    <select class="form-control @error('category_id') is-invalid @enderror" id="category_id" name="category_id" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Nama Produk <span class="text-danger">*</span></label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" placeholder="Masukkan nama produk" value="{{ old('name', $product->name) }}" required>
                    @error('name')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea class="form-control @error('description') is-invalid @enderror" id="description" name="description" placeholder="Masukkan deskripsi produk (opsional)" rows="3">{{ old('description', $product->description) }}</textarea>
                    @error('description')
                        <span class="invalid-feedback">{{ $message }}</span>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="price">Harga <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('price') is-invalid @enderror" id="price" name="price" placeholder="Masukkan harga produk" value="{{ old('price', $product->price) }}" min="0" required>
                            @error('price')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="stock">Stok <span class="text-danger">*</span></label>
                            <input type="number" class="form-control @error('stock') is-invalid @enderror" id="stock" name="stock" placeholder="Masukkan stok produk" value="{{ old('stock', $product->stock) }}" min="0" required>
                            @error('stock')
                                <span class="invalid-feedback">{{ $message }}</span>
                            @enderror
                        </div>
                    </div>
                </div>

                <div class="form-group">
                    <label for="image">Gambar Produk</label>
                    @if($product->image)
                        <div class="mb-3">
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                            <small class="d-block text-muted mt-2">Gambar saat ini</small>
                        </div>
                    @endif
                    <input type="file" class="form-control @error('image') is-invalid @enderror" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif">
                    <small class="form-text text-muted">Format: JPEG, PNG, JPG, GIF. Ukuran maksimal: 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</small>
                    @error('image')
                        <span class="invalid-feedback d-block">{{ $message }}</span>
                    @enderror
                    <div id="imagePreview" class="mt-3" style="display: none;">
                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px; max-height: 200px;">
                        <small class="d-block text-muted mt-2">Gambar baru</small>
                    </div>
                </div>

                <div class="form-group">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save fa-sm"></i> Simpan Perubahan
                    </button>
                    <a href="{{ route('products.index') }}" class="btn btn-secondary">
                        <i class="fas fa-times fa-sm"></i> Batal
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Image preview
        document.getElementById('image').addEventListener('change', function() {
            const file = this.files[0];
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('preview').src = e.target.result;
                    document.getElementById('imagePreview').style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                document.getElementById('imagePreview').style.display = 'none';
            }
        });
    </script>
@endsection
