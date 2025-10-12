@extends('layouts.app')

@section('title', 'Tambah Produk')

@section('content')
        <div class="main-content-wrap">

            {{-- === HEADER & BREADCRUMB === --}}
            <div class="flex items-center flex-wrap justify-between gap20 mb-27">
                <h3>Tambah Produk</h3>
                <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
                    <li>
                        <a href="{{ route('dashboard') }}">
                            <div class="text-tiny">Dashboard</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li>
                        <a href="{{ route('products.index') }}">
                            <div class="text-tiny">Produk</div>
                        </a>
                    </li>
                    <li><i class="icon-chevron-right"></i></li>
                    <li><div class="text-tiny">Tambah Produk</div></li>
                </ul>
            </div>

            {{-- === FORM TAMBAH PRODUK === --}}
            <form class="tf-section-2 form-add-product" action="{{ route('products.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- === BOX DETAIL PRODUK === --}}
                <div class="wg-box">
                    <fieldset class="name">
                        <div class="body-title mb-10">Nama Produk <span class="tf-color-1">*</span></div>
                        <input class="mb-10" type="text" name="name" value="{{ old('name') }}" placeholder="Masukkan nama produk" required>
                        <div class="text-tiny">Jangan melebihi 100 karakter untuk nama produk.</div>
                    </fieldset>

                    <div class="gap22 cols">
                        <fieldset class="category">
                            <div class="body-title mb-10">Kategori <span class="tf-color-1">*</span></div>
                            <div class="select">
                                <select name="category" required>
                                    <option value="">Pilih kategori</option>
                                    <option value="Makanan" {{ old('category')=='Makanan'?'selected':'' }}>Makanan</option>
                                    <option value="Minuman" {{ old('category')=='Minuman'?'selected':'' }}>Minuman</option>
                                    <option value="Lainnya" {{ old('category')=='Lainnya'?'selected':'' }}>Lainnya</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>

                    <div class="gap22 cols">
                        <fieldset class="price">
                            <div class="body-title mb-10">Harga (Rp) <span class="tf-color-1">*</span></div>
                            <input type="number" name="price" value="{{ old('price') }}" placeholder="Masukkan harga produk" required>
                        </fieldset>

                        <fieldset class="stock">
                            <div class="body-title mb-10">Stok Awal <span class="tf-color-1">*</span></div>
                            <input type="number" name="stock" value="{{ old('stock') }}" placeholder="Masukkan stok awal" required>
                        </fieldset>
                    </div>

                    <fieldset class="description">
                        <div class="body-title mb-10">Deskripsi Produk</div>
                        <textarea class="mb-10" name="description" placeholder="Tuliskan deskripsi produk (bahan, ukuran, varian, dll)">{{ old('description') }}</textarea>
                        <div class="text-tiny">Maksimal 255 karakter.</div>
                    </fieldset>
                </div>

                {{-- === BOX UPLOAD GAMBAR === --}}
                <div class="wg-box">
                    <fieldset>
                        <div class="body-title mb-10">Upload Gambar Produk</div>
                        <div class="upload-image mb-16">
                            <div class="item up-load">
                                <label class="uploadfile" for="image">
                                    <span class="icon">
                                        <i class="icon-upload-cloud"></i>
                                    </span>
                                    <span class="text-tiny">Drop gambar di sini atau <span class="tf-color">klik untuk memilih</span></span>
                                    <input type="file" id="image" name="image" accept="image/*" required>
                                </label>
                            </div>
                            <div class="item mt-2" id="previewWrapper" style="display:none;">
                                <img id="previewImage" src="#" alt="Preview" class="rounded" style="max-height:150px;">
                            </div>
                        </div>
                        <div class="body-text">Format JPG, JPEG, PNG — ukuran maksimal 2MB. Pastikan kualitas gambar baik.</div>
                    </fieldset>

                    <div class="cols gap22">
                        <fieldset class="name">
                            <div class="body-title mb-10">Tanggal Produk</div>
                            <div class="select">
                                <input type="date" name="product_date" value="{{ old('product_date', now()->format('Y-m-d')) }}">
                            </div>
                        </fieldset>

                        <fieldset class="name">
                            <div class="body-title mb-10">Status Produk</div>
                            <div class="select">
                                <select name="is_active">
                                    <option value="1" {{ old('is_active')=='1'?'selected':'' }}>Aktif</option>
                                    <option value="0" {{ old('is_active')=='0'?'selected':'' }}>Nonaktif</option>
                                </select>
                            </div>
                        </fieldset>
                    </div>

                    <div class="cols gap10">
                        <button class="tf-button w-full" type="submit">Tambah Produk</button>
                        <a href="{{ route('products.index') }}" class="tf-button style-1 w-full">Kembali</a>
                    </div>
                </div>
            </form>
        </div>
@endsection

@push('scripts')
<script>
document.getElementById('image')?.addEventListener('change', function(e){
    const file = e.target.files[0];
    if (file && file.type.startsWith('image/')) {
        const reader = new FileReader();
        reader.onload = ev => {
            const img = document.getElementById('previewImage');
            img.src = ev.target.result;
            document.getElementById('previewWrapper').style.display = 'block';
        };
        reader.readAsDataURL(file);
    }
});
</script>
@endpush
