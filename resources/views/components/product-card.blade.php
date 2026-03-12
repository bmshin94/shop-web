@props(['product'])

<div {{ $attributes->merge(['class' => 'group relative flex flex-col']) }}>
    <div class="relative aspect-[3/4] w-full overflow-hidden rounded-lg bg-gray-200 shadow-sm">
        <a href="{{ route('product-detail', ['slug' => $product->slug]) }}">
            <div class="h-full w-full bg-cover bg-center transition-transform duration-500 group-hover:scale-105 {{ $product->status === '품절' ? 'grayscale-[0.5] opacity-60' : '' }}"
                style="background-image: url('{{ $product->image_url ?? asset('images/placeholder.jpg') }}');"></div>
        </a>
        
        @if($product->status === '품절')
        <div class="absolute inset-0 flex items-center justify-center pointer-events-none">
            <span class="bg-black/60 text-white px-4 py-2 rounded-lg text-sm font-black border border-white/20 backdrop-blur-sm">SOLD OUT</span>
        </div>
        @endif
        
        <div class="absolute right-3 top-3 rounded-full bg-white/80 p-2 backdrop-blur-sm transition-colors hover:bg-primary hover:text-white cursor-pointer z-10 shadow-sm">
            <span class="material-symbols-outlined block text-lg {{ $product->is_wishlisted ? 'filled text-primary' : '' }}" 
                  style="{{ $product->is_wishlisted ? "font-variation-settings: 'FILL' 1;" : '' }}">favorite</span>
        </div>

        <div class="absolute top-3 left-3 flex flex-col gap-1.5">
            @if($product->is_best)
            <span class="inline-flex items-center gap-1 rounded-full bg-background-dark/90 backdrop-blur-md px-3 py-1 text-[10px] font-black text-yellow-400 shadow-xl tracking-tighter">
                <span class="material-symbols-outlined text-[12px] filled" style="font-variation-settings: 'FILL' 1;">star</span>
                BEST
            </span>
            @endif

            @if($product->is_new)
            <span class="inline-flex items-center gap-1.5 rounded-full bg-primary/90 backdrop-blur-md px-3 py-1 text-[10px] font-black text-white shadow-xl shadow-primary/20 tracking-tighter">
                <span class="size-1.5 rounded-full bg-white animate-pulse"></span>
                NEW
            </span>
            @endif
        </div>
    </div>

    <div class="mt-4 flex flex-1 flex-col px-1">
        <h4 class="text-base font-bold text-text-main hover:text-primary transition-colors">
            <a href="{{ route('product-detail', ['slug' => $product->slug]) }}">{{ $product->name }}</a>
        </h4>
        @if($product->brief_description)
        <p class="text-xs text-text-muted mt-1 mb-2 line-clamp-1">
            {{ $product->brief_description }}
        </p>
        @endif

        <!-- Color Options -->
        @if($product->colors->count() > 0)
        <div class="flex gap-1 py-1 mb-2">
            @foreach($product->colors as $color)
            <span class="size-3 rounded-full ring-1 ring-gray-200 shadow-sm" style="background-color: {{ $color->hex_code }}" title="{{ $color->name }}"></span>
            @endforeach
        </div>
        @endif

        <div class="mt-2 flex items-center justify-between">
            <div class="flex flex-col">
                @if($product->discount_rate > 0)
                <span class="text-xs text-red-500 font-bold">
                    {{ $product->discount_rate }}%
                    <span class="text-text-muted font-normal line-through ml-1 opacity-50">₩{{ number_format($product->price) }}</span>
                </span>
                <span class="text-lg font-bold text-text-main tracking-tight">₩{{ number_format($product->sale_price) }}</span>
                @else
                <span class="text-lg font-bold text-text-main tracking-tight">₩{{ number_format($product->price) }}</span>
                @endif
            </div>
        </div>
    </div>
</div>
