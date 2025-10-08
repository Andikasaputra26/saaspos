@forelse($storeProducts as $item)
    @php $product = $item->product; @endphp
    <li class="product-item gap14">
        <div class="image no-bg">
            <img src="{{ $product->image ? asset('storage/'.$product->image) : asset('assets/img/no-image.png') }}" 
                 alt="{{ $product->name }}">
        </div>
        <div class="flex items-center justify-between gap20 flex-grow">
            {{-- Nama --}}
            <div class="name">
                <a href="#" class="body-title-2">{{ $product->name }}</a>
            </div>

            {{-- Product ID --}}
            <div class="body-text">#{{ $product->sku ?? $item->id }}</div>

            {{-- Harga --}}
            <div class="body-text">Rp {{ number_format($item->price, 0, ',', '.') }}</div>

            {{-- Quantity --}}
            <div class="body-text">{{ $item->stock }}</div>

            {{-- Kategori --}}
            <div class="body-text">{{ $product->category ?? '-' }}</div>

            {{-- Stok --}}
            <div>
                @if($item->stock > 10)
                    <div class="block-available">Available</div>
                @elseif($item->stock > 0)
                    <div class="block-limited">Limited ({{ $item->stock }})</div>
                @else
                    <div class="block-not-available">Out of stock</div>
                @endif
            </div>

            {{-- Status --}}
            <div>
                @if($item->is_active)
                    <div class="block-available">Active</div>
                @else
                    <div class="block-not-available">Inactive</div>
                @endif
            </div>

            {{-- Aksi --}}
            <div class="list-icon-function">
                <a href="{{ route('products.show', $item->id) }}" class="item eye" title="Lihat">
                    <i class="icon-eye"></i>
                </a>
                <a href="{{ route('products.edit', $item->id) }}" class="item edit" title="Edit">
                    <i class="icon-edit-3"></i>
                </a>
                <form action="{{ route('products.destroy', $item->id) }}" method="POST" class="delete-form d-inline">
                    @csrf @method('DELETE')
                    <button type="submit" class="item trash" title="Hapus" style="border:none;background:none;">
                        <i class="icon-trash-2"></i>
                    </button>
                </form>
            </div>
        </div>
    </li>
@empty
    <li class="text-center py-4 text-muted">No products found.</li>
@endforelse
