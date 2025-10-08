@extends('layouts.app')

@section('title', 'Edit Produk')

@section('content')
<div class="main-content-wrap">

    {{-- === HEADER & BREADCRUMB === --}}
    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Edit Product</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li>
                <a href="{{ route('dashboard') }}"><div class="text-tiny">Dashboard</div></a>
            </li>
            <li><i class="icon-chevron-right"></i></li>
            <li><a href="#"><div class="text-tiny">Ecommerce</div></a></li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Edit Product</div></li>
        </ul>
    </div>

    {{-- === FORM EDIT PRODUK === --}}
    <form action="{{ route('products.update', $storeProduct->id) }}" method="POST" enctype="multipart/form-data" class="tf-section-2 form-add-product">
        @csrf
        @method('PUT')

        {{-- === BOX 1: INFORMASI PRODUK === --}}
        <div class="wg-box mb-5">
            <fieldset class="name">
                <div class="body-title mb-10">Product Name <span class="tf-color-1">*</span></div>
                <input type="text"
                       name="name"
                       placeholder="Enter product name"
                       value="{{ old('name', $storeProduct->product->name) }}"
                       required>
                <div class="text-tiny text-muted mt-1">Do not exceed 50 characters when entering the product name.</div>
                @error('name') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </fieldset>

            <div class="gap22 cols">
                <fieldset class="category">
                    <div class="body-title mb-10">Category</div>
                    <input type="text"
                           name="category"
                           placeholder="e.g. Drinks, Snacks, Accessories"
                           value="{{ old('category', $storeProduct->product->category) }}">
                    @error('category') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </fieldset>

                <fieldset class="price">
                    <div class="body-title mb-10">Price (Rp) <span class="tf-color-1">*</span></div>
                    <input type="number"
                           name="price"
                           min="0"
                           step="100"
                           placeholder="e.g. 25000"
                           value="{{ old('price', $storeProduct->price) }}"
                           required>
                    @error('price') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </fieldset>
            </div>

            <div class="gap22 cols">
                <fieldset class="stock">
                    <div class="body-title mb-10">Stock <span class="tf-color-1">*</span></div>
                    <input type="number"
                           name="stock"
                           min="0"
                           placeholder="Enter current stock"
                           value="{{ old('stock', $storeProduct->stock) }}"
                           required>
                    @error('stock') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
                </fieldset>

                <fieldset class="status">
                    <div class="body-title mb-10">Status</div>
                    <div class="select">
                        <select name="is_active" id="is_active">
                            <option value="1" {{ $storeProduct->is_active ? 'selected' : '' }}>Active</option>
                            <option value="0" {{ !$storeProduct->is_active ? 'selected' : '' }}>Inactive</option>
                        </select>
                    </div>
                </fieldset>
            </div>

            <fieldset class="description">
                <div class="body-title mb-10">Description</div>
                <textarea name="description"
                          rows="4"
                          placeholder="Enter details such as material, size, variants, or important notes">{{ old('description', $storeProduct->product->description) }}</textarea>
                @error('description') <div class="text-danger small mt-1">{{ $message }}</div> @enderror
            </fieldset>
        </div>

        {{-- === BOX 2: GAMBAR PRODUK === --}}
        <div class="wg-box">
            <fieldset>
                <div class="body-title mb-10">Product Image</div>
                <div class="upload-image mb-16">
                    <div class="item up-load" style="width:100%;">
                        <label class="uploadfile w-full cursor-pointer" for="image">
                            <div class="flex flex-col items-center justify-center py-6 border-2 border-dashed rounded-xl border-gray-300 hover:border-indigo-500 transition-all">
                                @if($storeProduct->product->image)
                                    <img id="previewImage"
                                         src="{{ asset('storage/' . $storeProduct->product->image) }}"
                                         alt="Preview Image"
                                         class="rounded-xl mb-3"
                                         style="max-height:200px;object-fit:cover;">
                                @else
                                    <img id="previewImage"
                                         src="https://cdn-icons-png.flaticon.com/512/685/685655.png"
                                         alt="Preview Image"
                                         class="rounded-xl mb-3"
                                         style="max-height:200px;object-fit:cover;">
                                @endif
                                <span class="icon mb-1"><i class="icon-upload-cloud"></i></span>
                                <span class="text-tiny">Drop your image here or <span class="tf-color">click to browse</span></span>
                                <input type="file" id="image" name="image" accept="image/*" hidden>
                            </div>
                        </label>
                    </div>
                </div>
                <div class="body-text text-muted text-center text-sm">
                    Format: JPG, JPEG, PNG • Maksimum 2 MB
                </div>
            </fieldset>

            <div class="divider my-4"></div>

            <div class="flex items-center justify-between flex-wrap gap10">
                <div class="text-tiny text-muted">
                    SKU: <strong>{{ $storeProduct->product->sku ?? '-' }}</strong>
                </div>
                <button class="tf-button style-1 w200" type="submit">
                    <i class="icon-save mr-2"></i> Save Changes
                </button>
            </div>
        </div>
    </form>
</div>
@endsection

@push('scripts')
<script>
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
/* === Form Input Styling === */
input[type="text"],
input[type="number"],
textarea,
select {
    border: 1px solid #d1d5db;
    border-radius: 0.6rem;
    padding: 8px 12px;
    width: 100%;
    font-size: 14px;
    transition: all 0.25s ease;
}
input:focus, textarea:focus, select:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.15);
}

/* === Button === */
.tf-button.style-1 {
    background-color: #2563eb;
    color: #fff;
    border-radius: 0.6rem;
    padding: 10px 20px;
    transition: all 0.25s ease;
}
.tf-button.style-1:hover {
    background-color: #1d4ed8;
    transform: translateY(-2px);
}

/* === Upload Area === */
.upload-image .uploadfile {
    cursor: pointer;
    transition: all 0.3s ease;
}
.upload-image .uploadfile:hover {
    background-color: #f3f4f6;
}

/* === Responsive === */
@media (max-width: 768px) {
    .cols {
        flex-direction: column !important;
    }
}
</style>
@endpush
