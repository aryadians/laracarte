<div>
    {{-- Header Toko --}}
    <div class="text-center mb-4">
        <h1 class="font-bold uppercase" style="font-size: 16px;">{{ $storeName }}</h1>
        <p style="font-size: 10px;">{{ $storeAddress }}</p>
    </div>

    {{-- Info Order --}}
    <div class="border-b pb-2 mb-2" style="font-size: 10px;">
        <div class="flex">
            <span>Order ID:</span>
            <span>#{{ $order->id }}</span>
        </div>
        <div class="flex">
            <span>Meja:</span>
            <span>{{ $order->table->name ?? 'Takeaway' }}</span>
        </div>
        <div class="flex">
            <span>Tgl:</span>
            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
        </div>
        <div class="flex">
            <span>Kasir:</span>
            <span>{{ auth()->user()->name ?? 'Admin' }}</span>
        </div>
    </div>

    {{-- List Item --}}
    <div class="mb-2">
        @foreach($order->items as $item)
        <div class="mb-1">
            <div class="font-bold">{{ $item->product->name }}</div>
            @if($item->selectedVariants->isNotEmpty())
                <div class="text-[8px] italic pl-2">
                    + {{ $item->selectedVariants->pluck('option_name')->join(', ') }}
                </div>
            @endif
            <div class="flex">
                <span>{{ $item->quantity }} x {{ number_format($item->price, 0, ',', '.') }}</span>
                <span>{{ number_format($item->quantity * $item->price, 0, ',', '.') }}</span>
            </div>
        </div>
        @endforeach
    </div>

    {{-- Total & Rincian --}}
    <div class="border-t border-b py-2 my-2">
        <div class="flex">
            <span>Subtotal</span>
            <span>{{ number_format($order->subtotal, 0, ',', '.') }}</span>
        </div>
        @if($order->service_charge > 0)
        <div class="flex">
            <span>Service</span>
            <span>{{ number_format($order->service_charge, 0, ',', '.') }}</span>
        </div>
        @endif
        @if($order->tax_amount > 0)
        <div class="flex">
            <span>Pajak</span>
            <span>{{ number_format($order->tax_amount, 0, ',', '.') }}</span>
        </div>
        @endif
        <div class="flex font-bold" style="font-size: 14px; margin-top: 5px;">
            <span>TOTAL</span>
            <span>{{ number_format($order->total_price, 0, ',', '.') }}</span>
        </div>
    </div>

    {{-- Footer --}}
    <div class="text-center mt-4">
        <p>Terima Kasih</p>
        <p style="font-size: 10px;">Silakan Datang Kembali</p>
        <br>
        <p style="font-size: 8px;">Powered by LaraCarte</p>
    </div>
</div>