@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
<div class="container py-4">

  {{-- === HEADER === --}}
  <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap">
    <div>
      <h2 class="fw-bold text-primary mb-1">Tambah Produk Baru</h2>
      <p class="text-muted small mb-0">
        Lengkapi informasi produk yang akan dijual di toko
        <strong>{{ \App\Models\Store::find(session('store_id'))->name ?? 'Anda' }}</strong>.
      </p>
    </div>
    <a href="{{ route('products.index') }}" class="btn btn-outline-secondary btn-sm mt-2 mt-md-0">
      <i class="bi bi-arrow-left-circle me-1"></i> Kembali
    </a>
  </div>

  {{-- === FORM TAMBAH PRODUK === --}}
  <div class="card border-0 shadow-sm rounded-4">
    <div class="card-body p-4">
      <form action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="row g-4">

          {{-- === Kolom kiri: Detail produk === --}}
          <div class="col-md-7">

            {{-- Nama Produk --}}
            <div class="mb-3">
              <label for="name" class="form-label fw-semibold">Nama Produk <span class="text-danger">*</span></label>
              <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror"
                     value="{{ old('name') }}" placeholder="Contoh: Kopi Arabica Premium" required>
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Kategori --}}
            <div class="mb-3">
              <label for="category" class="form-label fw-semibold">Kategori</label>
              <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror"
                     value="{{ old('category') }}" placeholder="Contoh: Minuman, Snack, Aksesoris">
              @error('category')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Harga --}}
            <div class="mb-3">
              <label for="price" class="form-label fw-semibold">Harga (Rp) <span class="text-danger">*</span></label>
              <input type="number" name="price" id="price" min="0" step="100"
                     class="form-control @error('price') is-invalid @enderror"
                     value="{{ old('price') }}" placeholder="Contoh: 25000" required>
              @error('price')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mb-3">
              <label for="is_active" class="form-label">Status Produk</label>
              <select name="is_active" id="is_active" class="form-select">
                <option value="1" selected>Aktif</option>
                <option value="0">Nonaktif</option>
              </select>
            </div>

            {{-- Stok --}}
            <div class="mb-3">
              <label for="stock" class="form-label fw-semibold">Stok Awal <span class="text-danger">*</span></label>
              <input type="number" name="stock" id="stock" min="0"
                     class="form-control @error('stock') is-invalid @enderror"
                     value="{{ old('stock') }}" placeholder="Contoh: 10" required>
              @error('stock')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            {{-- Deskripsi --}}
            <div class="mb-3">
              <label for="description" class="form-label fw-semibold">Deskripsi Produk</label>
              <textarea name="description" id="description" rows="4"
                        class="form-control @error('description') is-invalid @enderror"
                        placeholder="Tuliskan detail seperti bahan, ukuran, varian, atau catatan penting">{{ old('description') }}</textarea>
              @error('description')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

          </div>

          {{-- === Kolom kanan: Upload gambar === --}}
          <div class="col-md-5 text-center">
            <label class="form-label fw-semibold d-block mb-2">Gambar Produk</label>

            <div class="upload-container mb-3">
              <img id="previewImage"
                   src="https://cdn-icons-png.flaticon.com/512/685/685655.png"
                   alt="Preview Gambar"
                   class="img-thumbnail rounded-4 shadow-sm"
                   style="max-height: 230px; object-fit: cover;">

              <input type="file" name="image" id="image" accept="image/*"
                     class="form-control mt-3 @error('image') is-invalid @enderror">
              @error('image')
                <div class="invalid-feedback d-block">{{ $message }}</div>
              @enderror
            </div>

            <small class="text-muted d-block">
              Format: JPG, JPEG, PNG &nbsp; • &nbsp; Maksimal 2 MB
            </small>
          </div>
        </div>

        <hr class="my-4">

        {{-- Tombol Simpan --}}
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-success px-4 py-2">
            <i class="bi bi-check-circle me-1"></i> Simpan Produk
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
  // Preview gambar sebelum upload
  document.getElementById('image')?.addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
      const reader = new FileReader();
      reader.onload = ev => document.getElementById('previewImage').src = ev.target.result;
      reader.readAsDataURL(file);
    }
  });
</script>
@endpush

@push('styles')
<style>
  input.form-control, textarea.form-control {
    border-radius: 0.6rem;
  }

  .btn-success {
    border-radius: 0.6rem;
    transition: all 0.25s ease;
  }

  .btn-success:hover {
    transform: translateY(-2px);
    box-shadow: 0 6px 12px rgba(25,135,84,0.3);
  }

  .upload-container {
    border: 2px dashed var(--bs-border-color);
    border-radius: 1rem;
    padding: 15px;
    background-color: var(--bs-light-bg-subtle, #f8f9fa);
    transition: 0.3s;
  }

  .upload-container:hover {
    background-color: var(--bs-gray-100);
  }

  @media (max-width: 768px) {
    .upload-container {
      margin-top: 1rem;
    }
  }
</style>
@endpush
