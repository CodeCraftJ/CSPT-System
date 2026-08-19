<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">Orders</div>
                <h2 class="page-title">Order Queue</h2>
                <p class="page-subtitle">Complete orders in first-come-first-serve order.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">{{ $errors->first() }}</div>
        @endif

        <section class="app-surface p-6">
            <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">First come, first serve</h3>
                    <p class="mt-1 text-sm text-slate-500">Pending orders are sorted oldest first.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('orders.create') }}" class="app-btn-primary">Create order</a>
                    <form method="POST" action="{{ route('orders.export') }}">
                        @csrf
                        <button type="submit" class="app-btn-secondary">Export orders</button>
                    </form>
                </div>
            </div>
        </section>

        <section class="app-surface overflow-hidden">
            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Order</th>
                            <th>Staff Member</th>
                            <th>Items</th>
                            <th class="text-right">Total</th>
                            <th>Status</th>
                            <th class="text-right">Date</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($orders as $order)
                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-900">#{{ $order->id }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ $order->user->name }}</td>
                                <td class="px-6 py-4 text-slate-700">{{ $order->orderItems->map(fn($item) => $item->foodItem->name.' x'.$item->quantity)->join(', ') }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right font-semibold text-slate-900">₱{{ number_format($order->total_price, 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4">
                                    <span class="app-badge {{ $order->status === 'completed' ? 'bg-emerald-100 text-emerald-800' : 'bg-amber-100 text-amber-800' }}">{{ ucfirst($order->status) }}</span>
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-slate-500">{{ $order->created_at->format('M d, Y h:i A') }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <div class="inline-flex flex-wrap items-center gap-3">
                                        <a href="{{ route('orders.show', $order) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">View</a>
                                        <a href="{{ route('orders.edit', $order) }}" class="text-sm font-semibold text-slate-700 hover:text-slate-900">Edit</a>
                                        <form method="POST" action="{{ route('orders.update_status', $order) }}">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="{{ $order->status === 'completed' ? 'pending' : 'completed' }}">
                                            <button type="submit" class="text-sm font-semibold {{ $order->status === 'completed' ? 'text-amber-600 hover:text-amber-700' : 'text-emerald-600 hover:text-emerald-700' }}">
                                                {{ $order->status === 'completed' ? 'Mark pending' : 'Complete' }}
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('orders.destroy', $order) }}" onsubmit="return confirm('Delete this order?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-6 py-10 text-center text-sm text-slate-500">No orders available.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
