@extends('layouts.app')

@section('title', 'Tambah Toko')

@section('content')
<div class="main-content-wrap">

    <div class="flex items-center flex-wrap justify-between gap20 mb-27">
        <h3>Tambah Toko Baru</h3>
        <ul class="breadcrumbs flex items-center flex-wrap justify-start gap10">
            <li>
                <a href="{{ route('dashboard') }}">
                    <div class="text-tiny">Dashboard</div>
                </a>
            </li>
            <li><i class="icon-chevron-right"></i></li>
            <li>
                <a href="{{ route('stores.index') }}">
                    <div class="text-tiny">Toko</div>
                </a>
            </li>
            <li><i class="icon-chevron-right"></i></li>
            <li><div class="text-tiny">Tambah Toko</div></li>
        </ul>
    </div>

    <div class="wg-box max-w-2xl mx-auto">
        <div class="title-box mb-6">
            <i class="icon-store"></i>
            <div class="body-text">
                Lengkapi informasi toko di bawah untuk mulai berjualan. 
                Semua data bisa diubah nanti melalui halaman edit toko.
            </div>
        </div>

        <form action="{{ route('stores.store') }}" method="POST" class="space-y-5">
            @csrf

            <div>
                <label for="name" class="body-title mb-2">Nama Toko <span class="text-red-500">*</span></label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       class="form-control w-full"
                       placeholder="Contoh: Toko Cell"
                       value="{{ old('name') }}"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="address" class="body-title mb-2">Alamat Toko</label>
                <textarea name="address" 
                          id="address" 
                          rows="3" 
                          class="form-control w-full" 
                          placeholder="Jl. Merdeka No. 10, Banjar">{{ old('address') }}</textarea>
                @error('address')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label for="phone" class="body-title mb-2">Nomor Telepon</label>
                <input type="text" 
                       name="phone" 
                       id="phone" 
                       class="form-control w-full"
                       placeholder="08123456789"
                       value="{{ old('phone') }}">
                @error('phone')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex items-center justify-between pt-4">
                <a href="{{ route('stores.index') }}" class="tf-button style-2">
                    <i class="icon-arrow-left"></i> Kembali
                </a>

                <button type="submit" class="tf-button style-1">
                    <i class="icon-save"></i> Simpan Toko
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@push('styles')
<style>
.form-control {
    border: 1px solid #d1d5db;
    border-radius: 8px;
    padding: 10px 12px;
    font-size: 14px;
    color: #111827;
    transition: all 0.2s ease-in-out;
}
.form-control:focus {
    border-color: #2563eb;
    box-shadow: 0 0 0 2px rgba(37,99,235,0.2);
    outline: none;
}
.body-title {
    font-weight: 600;
    color: #374151;
    font-size: 14px;
}
.tf-button.style-2 {
    background-color: #f3f4f6;
    color: #374151;
    border: 1px solid #d1d5db;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.2s;
}
.tf-button.style-2:hover {
    background-color: #e5e7eb;
}
.tf-button.style-1 {
    background-color: #2563eb;
    color: white;
    padding: 8px 16px;
    border-radius: 8px;
    font-weight: 600;
    transition: 0.2s;
}
.tf-button.style-1:hover {
    background-color: #1e40af;
}
</style>
@endpush
