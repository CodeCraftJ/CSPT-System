<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">Receipt</div>
                <h2 class="page-title">Order #{{ $order->id }}</h2>
                <p class="page-subtitle">Placed {{ $order->created_at->format('M d, Y h:i A') }} by {{ $order->user->name }}.</p>
            </div>
            <span class="app-badge {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ ucfirst($order->status) }}</span>
        </div>
    </x-slot>

    <div class="mx-auto max-w-4xl space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif

        <section class="app-surface overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <h3 class="text-lg font-semibold text-slate-900">Order details</h3>
                <p class="mt-1 text-sm text-slate-500">{{ $order->status === 'completed' ? 'This order has been completed.' : 'This order is awaiting completion.' }}</p>
            </div>

            <div class="divide-y divide-slate-200">
                @foreach($order->orderItems as $item)
                    <div class="flex flex-col gap-4 p-6 sm:flex-row sm:items-center sm:justify-between">
                        <div class="flex items-center gap-4">
                            <div class="h-16 w-16 shrink-0 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                @if($item->foodItem->image_url)
                                    <img src="{{ $item->foodItem->image_url }}" alt="{{ $item->foodItem->name }}" class="h-full w-full object-cover">
                                @else
                                    <div class="flex h-full w-full items-center justify-center text-xs font-semibold text-slate-400">No image</div>
                                @endif
                            </div>
                            <div>
                                <p class="font-semibold text-slate-900">{{ $item->foodItem->name }}</p>
                                <p class="text-sm text-slate-500">{{ $item->quantity }} x ₱{{ number_format($item->unit_price, 2) }}</p>
                            </div>
                        </div>
                        <p class="text-right text-sm font-semibold text-slate-900">₱{{ number_format($item->total_price, 2) }}</p>
                    </div>
                @endforeach
            </div>

            <div class="flex items-center justify-between border-t border-slate-200 bg-slate-50 px-6 py-5">
                <span class="text-sm font-semibold text-slate-600">Total</span>
                <span class="text-2xl font-semibold text-slate-900">₱{{ number_format($order->total_price, 2) }}</span>
            </div>
        </section>

        <div class="flex flex-wrap justify-end gap-3">
            <a href="{{ route('orders.index') }}" class="app-btn-secondary">Back to orders</a>
            @if($isStaff)
                <a href="{{ route('orders.edit', $order) }}" class="app-btn-secondary">Edit order</a>
                <form method="POST" action="{{ route('orders.update_status', $order) }}">
                    @csrf
                    @method('PATCH')
                    <input type="hidden" name="status" value="{{ $order->status === 'completed' ? 'pending' : 'completed' }}">
                    <button type="submit" class="{{ $order->status === 'completed' ? 'app-btn-secondary' : 'app-btn-primary' }}">
                        {{ $order->status === 'completed' ? 'Mark pending' : 'Complete order' }}
                    </button>
                </form>
                <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="app-btn-secondary text-rose-600 hover:text-rose-700">Delete order</button>
                </form>
            @endif
        </div>
    </div>
</x-app-layout>
