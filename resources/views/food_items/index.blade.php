<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">Management</div>
                <h2 class="page-title">Food Items</h2>
                <p class="page-subtitle">Manage the canteen menu with clearer item detail, pricing, and stock visibility.</p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-6">
        <section class="app-surface p-6">
            <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
                <div>
                    <h3 class="text-lg font-semibold text-slate-900">Menu management</h3>
                    <p class="mt-1 text-sm text-slate-500">Add, edit, or remove food items with fast access to export and create actions.</p>
                </div>
                <div class="flex flex-wrap gap-3">
                    <a href="{{ route('food_items.create') }}" class="app-btn-primary">Add item</a>
                    <form method="POST" action="{{ route('food_items.export') }}">
                        @csrf
                        <button type="submit" class="app-btn-secondary">Export CSV</button>
                    </form>
                    <a href="{{ route('food_items.report') }}" target="_blank" class="app-btn-secondary">Printable report</a>
                </div>
            </div>
        </section>

        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        <section class="app-surface overflow-hidden">
            <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                <h3 class="text-lg font-semibold text-slate-900">Food items list</h3>
                <p class="mt-1 max-w-2xl text-sm text-slate-500">Track prices, quantities, categories, and stock levels.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="app-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Snack type</th>
                            <th class="text-right">Price</th>
                            <th class="text-right">Cost</th>
                            <th class="text-right">Qty</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-slate-200 bg-white">
                        @forelse($items as $item)
                            <tr class="transition hover:bg-slate-50">
                                <td class="whitespace-nowrap px-6 py-4">
                                    @if($item->image_url)
                                        <div class="h-12 w-12 overflow-hidden rounded-2xl border border-slate-200 bg-slate-50">
                                            <img src="{{ $item->image_url }}" alt="{{ $item->name }}" class="h-full w-full object-cover" />
                                        </div>
                                    @else
                                        <span class="text-xs text-slate-400">No image</span>
                                    @endif
                                </td>
                                <td class="whitespace-nowrap px-6 py-4 font-medium text-slate-900">{{ $item->name }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ $item->category }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ $item->snack_type ?? 'N/A' }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right font-semibold text-slate-900">₱{{ number_format($item->price, 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-slate-500">₱{{ number_format($item->cost, 2) }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right text-slate-900">{{ $item->quantity }}</td>
                                <td class="whitespace-nowrap px-6 py-4 text-right">
                                    <div class="inline-flex items-center gap-3">
                                        <a href="{{ route('food_items.edit', $item) }}" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">Edit</a>
                                        <form method="POST" action="{{ route('food_items.destroy', $item) }}" onsubmit="return confirm('Delete this food item?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700">Delete</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="8" class="px-6 py-10 text-center text-sm text-slate-500">No food items found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</x-app-layout>
