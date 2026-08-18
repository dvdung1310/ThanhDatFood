<article class="product-card">
    <a class="product-image" href="{{ route('products.show', $product->slug) }}">
        @if($product->primary_image)<img src="{{ $product->primary_image->url }}" alt="{{ $product->primary_image->alt_text ?: $product->name }}">@else<span>🌿</span>@endif
        @if($product->discount_percentage)<em>-{{ $product->discount_percentage }}%</em>@elseif($product->is_featured)<em>Nổi bật</em>@endif
    </a>
    <div class="product-body">
        <small>{{ $product->category?->name }}@if($product->origin) · {{ $product->origin }}@endif</small>
        <h3><a href="{{ route('products.show', $product->slug) }}">{{ $product->name }}</a></h3>
        <div class="product-price-row"><strong>{{ number_format($product->price, 0, ',', '.') }}đ</strong>@if($product->original_price && $product->original_price > $product->price)<del>{{ number_format($product->original_price, 0, ',', '.') }}đ</del>@endif</div>
        <span class="product-unit">/ {{ $product->unit }}</span><a class="round-btn" href="{{ route('products.show', $product->slug) }}" aria-label="Xem {{ $product->name }}">＋</a>
    </div>
</article>
