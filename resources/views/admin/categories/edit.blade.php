@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h1 class="h3 mb-4" style="font-family: 'Playfair Display', serif;">Edit Kategori</h1>
    
    <div class="card shadow-sm border-0" style="border-radius: 0.75rem;">
        <div class="card-body p-4">
            <form action="{{ route('admin.categories.update', $category) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="mb-3">
                    <label for="nama_kategori" class="form-label">Nama Kategori</label>
                    <input type="text" class="form-control @error('nama_kategori') is-invalid @enderror" 
                           id="nama_kategori" name="nama_kategori" 
                           value="{{ old('nama_kategori', $category->nama_kategori) }}" required>
                    @error('nama_kategori')
                        <div class="invalid-feedback">
                            {{ $message }}
                        </div>
                    @enderror
                </div>
                
                <div class->
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-custom">Update Kategori</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection