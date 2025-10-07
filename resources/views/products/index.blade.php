@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="container py-4">

  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h2 class="fw-bold text-primary mb-1">Daftar Produk</h2>
      <p class="text-muted small mb-0">
        Kelola seluruh produk toko 
        <strong>{{ optional(\App\Models\Store::find(session('store_id')))->name ?? 'Toko Anda' }}</strong>.
      </p>
    </div>

    <div class="d-flex align-items-center gap-2 mt-2 mt-md-0">
      <form action="{{ route('products.index') }}" method="GET" class="d-flex">
        <input type="text" name="search" value="{{ request('search') }}" 
               class="form-control form-control-sm me-2" placeholder="Cari produk...">
        <button class="btn btn-outline-primary btn-sm" type="submit">
          <i class="bi bi-search"></i>
        </button>
      </form>
      <a href="{{ route('products.create') }}" class="btn btn-primary btn-sm">
        <i class="bi bi-plus-circle me-1"></i> Tambah Produk
      </a>
    </div>
  </div>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm" role="alert">
      <i class="bi bi-check-circle-fill me-2"></i> {{ session('success') }}
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
  @endif

  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-0">
      <div class="table-responsive">
        <table class="table table-hover mb-0 align-middle">
          <thead class="table-light">
            <tr>
              <th scope="col" class="text-center" width="60">#</th>
              <th>Gambar</th>
              <th>Nama Produk</th>
              <th>Kategori</th>
              <th class="text-end">Harga</th>
              <th class="text-center">Stok</th>
              <th class="text-center" width="150">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($storeProducts as $index => $item)
              @php $product = $item->product; @endphp
              <tr>
                <td class="text-center">{{ $loop->iteration + ($storeProducts->firstItem() - 1) }}</td>

                <td>
                  @if(!empty($product?->image))
                    <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('assets/img/no-image.png') }}"
                         alt="Product Image" width="50" height="50"
                         class="rounded-3 object-fit-cover shadow-sm">
                  @else
                    <div class="bg-light d-flex justify-content-center align-items-center rounded-3 border"
                         style="width:50px;height:50px;">
                      <i class="bi bi-image text-muted fs-5"></i>
                    </div>
                  @endif
                </td>

                <td>
                  <div class="fw-semibold text-truncate" style="max-width: 180px;">
                    {{ $product->name ?? '-' }}
                  </div>
                  <small class="text-muted">SKU: {{ $product->sku ?? '-' }}</small>
                </td>

                <td class="text-capitalize">{{ $product->category ?? '-' }}</td>

                <td class="text-end">
                  <span class="fw-semibold">Rp {{ number_format($item->price, 0, ',', '.') }}</span>
                </td>

                <td class="text-center">
                  @if($item->stock > 10)
                    <span class="badge bg-success bg-opacity-10 text-success px-3">{{ $item->stock }}</span>
                  @elseif($item->stock > 0)
                    <span class="badge bg-warning bg-opacity-10 text-warning px-3">{{ $item->stock }}</span>
                  @else
                    <span class="badge bg-danger bg-opacity-10 text-danger px-3">Habis</span>
                  @endif
                </td>

                <td class="text-center">
                  <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('products.edit', $item->id) }}" 
                       class="btn btn-outline-primary" title="Edit Produk">
                      <i class="bi bi-pencil-square"></i>
                    </a>
                    <form action="{{ route('products.destroy', $item->id) }}" 
                          method="POST" class="d-inline delete-form">
                      @csrf
                      @method('DELETE')
                      <button type="submit" class="btn btn-outline-danger" title="Hapus Produk">
                        <i class="bi bi-trash"></i>
                      </button>
                    </form>
                  </div>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center py-4">
                  <i class="bi bi-box-seam text-muted fs-3 d-block mb-2"></i>
                  <span class="text-muted">Belum ada produk di toko ini.</span>
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </div>
  </div>

  <div class="mt-3">
    {{ $storeProducts->links('pagination::bootstrap-5') }}
  </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.querySelectorAll('.delete-form').forEach(form => {
    form.addEventListener('submit', function(e) {
      e.preventDefault();
      Swal.fire({
        title: 'Yakin hapus produk ini?',
        text: 'Data produk akan dihapus permanen dari toko!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          this.submit();
        }
      });
    });
  });
</script>
@endpush

@push('styles')
<style>
  table td, table th {
    vertical-align: middle !important;
  }
  .object-fit-cover {
    object-fit: cover;
  }
  .btn-group .btn { min-width: 36px; }
</style>
@endpush
