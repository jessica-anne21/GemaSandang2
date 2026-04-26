@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4" style="font-family: 'Playfair Display', serif;">Edit Produk</h1>
    
    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="card-body p-4">
            <form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="nama_produk" class="form-label">Nama Produk</label>
                    <input type="text" class="form-control @error('nama_produk') is-invalid @enderror" 
                           id="nama_produk" name="nama_produk" value="{{ old('nama_produk', $product->nama_produk) }}" required>
                    @error('nama_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class="mb-3">
                    <label for="category_id" class="form-label">Kategori</label>
                    <select class="form-select @error('category_id') is-invalid @enderror" 
                            id="category_id" name="category_id" required>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
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
                               id="harga" name="harga" value="{{ old('harga', $product->harga) }}" required>
                        @error('harga') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="stok" class="form-label">Stok</label>
                        <input type="number" class="form-control @error('stok') is-invalid @enderror" 
                               id="stok" name="stok" value="{{ old('stok', $product->stok) }}" required>
                        @error('stok') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi (Opsional)</label>
                    <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3">{{ old('deskripsi', $product->deskripsi) }}</textarea>
                </div>
                
                <div class="mb-3">
                    <label for="foto_produk" class="form-label">Ganti Foto Produk (Opsional)</label>
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $product->foto_produk) }}" alt="Foto lama" class="img-thumbnail" width="150">
                    </div>
                    <input type="file" class="form-control @error('foto_produk') is-invalid @enderror" 
                           id="foto_produk" name="foto_produk">
                    <small class="text-muted">Biarkan kosong jika tidak ingin mengganti foto.</small>
                    @error('foto_produk') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
                
                <div class->
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-custom">Update Produk</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection