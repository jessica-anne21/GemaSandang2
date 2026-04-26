<form action="{{ route('product.search') }}" method="GET" class="d-flex w-100">
    <div class="input-group">
        <input type="text" name="query" class="form-control border-end-0" 
               placeholder="Cari produk..." 
               value="{{ request('query') }}" 
               aria-label="Cari produk"
               style="border-radius: 50px 0 0 50px; padding-left: 20px;">
        <button class="btn btn-outline-secondary border-start-0" type="submit" 
                style="border-radius: 0 50px 50px 0; padding-right: 20px; background-color: white;">
            <i class="bi bi-search text-muted"></i>
        </button>
    </div>
</form>