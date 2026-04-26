@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4" style="font-family: 'Playfair Display', serif;">Tambah Produk Baru</h1>
    
    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="card-body p-4">
            <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                           id="nama_produk" name="nama_produk" value="{{ old('nama_produk') }}" required>
                    @error('nama_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" 
                            id="category_id" name="category_id" required>
                        <option value="" disabled selected>Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->nama_kategori }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="harga" class="form-label">Harga</label>
                        <input type="number" class="form-control @error('harga') is-invalid @enderror" 
                               id="harga" name="harga" value="{{ old('harga') }}" required>
                        @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control bg-light" 
                               id="stok" name="stok" value="1" readonly>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
                </div>

                <div class="mb-3">
                    <label for="warna" class="form-label">Warna</label>
                    <textarea class="form-control" id="warna" name="warna" rows="3">{{ old('warna') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="style" class="form-label">Style</label>
                    <textarea class="form-control" id="style" name="style" rows="3">{{ old('style') }}</textarea>
                </div>
                <div class="mb-3">
                    <label for="material" class="form-label">Material</label>
                    <textarea class="form-control" id="material" name="material" rows="3">{{ old('material') }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="foto_produk" class="form-label">Foto Produk</label>
                    <input type="file" class="form-control @error('foto_produk') is-invalid @enderror" 
                           id="foto_produk" name="foto_produk" required>
                    @error('foto_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="text-end mt-4">
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary px-4 rounded-pill">Batal</a>
                    <button type="submit" class="btn btn-custom px-4 rounded-pill">Simpan Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection