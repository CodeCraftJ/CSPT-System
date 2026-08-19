<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">{{ ucfirst($user->role ?: 'user') }} dashboard</div>
                <h2 class="page-title">Dashboard</h2>
                <p class="page-subtitle">
                    Monitor stock, order queue, sales by type, and completed-order profit.
                </p>
            </div>
        </div>
    </x-slot>

    <div class="space-y-8">
        @if(session('success'))
            <div class="rounded-3xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-sm text-emerald-800">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="rounded-3xl border border-rose-200 bg-rose-50 px-5 py-4 text-sm text-rose-700">
                {{ $errors->first() }}
            </div>
        @endif

        <section class="app-card overflow-hidden bg-slate-950 text-white">
            <div class="bg-gradient-to-br from-white/10 via-transparent to-transparent p-8 sm:p-10">
                <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4">
                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-300">Food items</p>
                        <p class="mt-4 text-3xl font-semibold">{{ $totalItems }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-300">Pending queue</p>
                        <p class="mt-4 text-3xl font-semibold">{{ $pendingOrders->count() }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-300">Sales</p>
                        <p class="mt-4 text-3xl font-semibold">₱{{ number_format($totalRevenue, 2) }}</p>
                    </div>
                    <div class="rounded-3xl border border-white/10 bg-white/10 p-5">
                        <p class="text-xs font-semibold uppercase tracking-[0.25em] text-slate-300">Profit</p>
                        <p class="mt-4 text-3xl font-semibold text-emerald-300">₱{{ number_format($totalProfit, 2) }}</p>
                    </div>
                </div>
            </div>
        </section>

        @if($user->role === 'admin' || $user->role === 'staff')
            <section class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">
                <div class="app-surface p-6">
                    <p class="page-kicker">Today</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Sales</h3>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">₱{{ number_format($dailySales, 2) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Profit: ₱{{ number_format($dailyProfit, 2) }}</p>
                </div>

                <div class="app-surface p-6">
                    <p class="page-kicker">This month</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Sales</h3>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">₱{{ number_format($monthlySales, 2) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Profit: ₱{{ number_format($monthlyProfit, 2) }}</p>
                </div>

                <div class="app-surface p-6">
                    <p class="page-kicker">This year</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Sales</h3>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">₱{{ number_format($yearlySales, 2) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Profit: ₱{{ number_format($yearlyProfit, 2) }}</p>
                </div>

                <div class="app-surface p-6">
                    <p class="page-kicker">Products</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Sold</h3>
                    <p class="mt-3 text-3xl font-semibold text-slate-900">{{ number_format($totalProductsSold) }}</p>
                    <p class="mt-2 text-sm text-slate-500">Completed order items across the system.</p>
                </div>
            </section>
        @endif

        @if($user->role === 'admin' || $user->role === 'staff')
            <section class="grid gap-6 lg:grid-cols-3">
                <a href="{{ route('orders.create') }}" class="app-surface app-card-interactive p-6">
                    <p class="page-kicker">Orders</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Create order</h3>
                    <p class="mt-3 text-sm text-slate-500">Enter customer items, quantities, and automatic totals.</p>
                </a>

                <a href="{{ route('orders.index') }}" class="app-surface app-card-interactive p-6">
                    <p class="page-kicker">Orders</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Manage orders</h3>
                    <p class="mt-3 text-sm text-slate-500">Review, edit, complete, or delete orders entered by staff.</p>
                </a>

                <a href="{{ route('food_items.index') }}" class="app-surface app-card-interactive p-6">
                    <p class="page-kicker">Inventory</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Manage menu</h3>
                    <p class="mt-3 text-sm text-slate-500">Maintain product names, types, prices, and stock counts.</p>
                </a>
            </section>

            <section class="grid gap-6 lg:grid-cols-3">
                <a href="{{ route('food_items.create') }}" class="app-surface app-card-interactive p-6">
                    <p class="page-kicker">Inventory</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Add food item</h3>
                    <p class="mt-3 text-sm text-slate-500">Create menu items with price, cost, stock, and product image.</p>
                </a>

                <a href="{{ route('food_items.index') }}" class="app-surface app-card-interactive p-6">
                    <p class="page-kicker">Menu</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Manage supplies</h3>
                    <p class="mt-3 text-sm text-slate-500">Review quantities and product details.</p>
                </a>

                <form method="POST" action="{{ route('food_items.export') }}" class="app-surface app-card-interactive p-6">
                    @csrf
                    <p class="page-kicker">Report</p>
                    <h3 class="mt-4 text-lg font-semibold text-slate-900">Download sold items CSV</h3>
                    <p class="mt-3 text-sm text-slate-500">Export item type, description, quantity sold, and sales totals.</p>
                    <button type="submit" class="app-btn-primary mt-5">Download CSV</button>
                </form>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="app-surface p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">First come, first serve queue</h3>
                            <p class="mt-1 text-sm text-slate-500">Oldest pending orders appear first for completion.</p>
                        </div>
                        <a href="{{ route('orders.index') }}" class="app-btn-secondary">View all</a>
                    </div>

                    <div class="mt-5 space-y-3">
                        @forelse($pendingOrders as $order)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                                    <div>
                                        <p class="font-semibold text-slate-900">Order #{{ $order->id }} · {{ $order->user->name }}</p>
                                        <p class="text-sm text-slate-500">{{ $order->created_at->format('M d, Y h:i A') }} · ₱{{ number_format($order->total_price, 2) }}</p>
                                    </div>
                                    <form method="POST" action="{{ route('orders.update_status', $order) }}">
                                        @csrf
                                        @method('PATCH')
                                        <input type="hidden" name="status" value="completed">
                                        <button class="app-btn-primary" type="submit">Complete</button>
                                    </form>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No pending orders in the queue.</p>
                        @endforelse
                    </div>
                </div>

                <div class="app-surface p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Sold supplies by type</h3>
                    <div class="mt-5 space-y-3">
                        @forelse($soldByType as $type)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <div class="flex items-center justify-between gap-4">
                                    <div>
                                        <p class="font-semibold text-slate-900">{{ $type->type }}</p>
                                        <p class="text-sm text-slate-500">{{ (int) $type->quantity_sold }} sold · ₱{{ number_format($type->total_sold, 2) }} sales</p>
                                    </div>
                                    <span class="text-sm font-semibold text-emerald-700">₱{{ number_format($type->profit, 2) }} profit</span>
                                </div>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">No completed sales yet.</p>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="grid gap-6 lg:grid-cols-2">
                <div class="app-surface p-6">
                    <div class="flex items-center justify-between gap-4">
                        <div>
                            <h3 class="text-lg font-semibold text-slate-900">Low stock alerts</h3>
                            <p class="mt-1 text-sm text-slate-500">Track the items that need replenishing soon.</p>
                        </div>
                        <span class="app-badge bg-rose-50 text-rose-700">Alert</span>
                    </div>
                    <div class="mt-5 space-y-3">
                        @forelse($lowStockItems as $item)
                            <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                <p class="font-semibold text-slate-900">{{ $item->name }}</p>
                                <p class="text-sm text-slate-500">{{ $item->quantity }} left · {{ $item->category }}</p>
                            </div>
                        @empty
                            <p class="text-sm text-slate-500">All supplies are stocked normally.</p>
                        @endforelse
                    </div>
                </div>

                <div class="app-surface p-6">
                    <h3 class="text-lg font-semibold text-slate-900">Latest food items</h3>
                    <ul role="list" class="mt-5 divide-y divide-slate-200">
                        @foreach($foodItems->take(6) as $item)
                            <li class="flex items-center justify-between py-4">
                                <div>
                                    <p class="font-semibold text-slate-900">{{ $item->name }}</p>
                                    <p class="text-sm text-slate-500">{{ $item->category }} · ₱{{ number_format($item->price, 2) }}</p>
                                </div>
                                <span class="text-sm text-slate-500">{{ $item->quantity }} pcs</span>
                            </li>
                        @endforeach
                    </ul>
                </div>
            </section>
        @endif

        @if($user->role === 'admin')
            <section class="app-surface overflow-hidden">
                <div class="border-b border-slate-200 bg-slate-50 px-6 py-5">
                    <h3 class="text-lg font-semibold text-slate-900">Manage staff</h3>
                    <p class="mt-1 text-sm text-slate-500">Disable access or remove staff accounts.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="app-table">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Status</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-200 bg-white">
                            @foreach($managedUsers as $managedUser)
                                <tr>
                                    <td class="whitespace-nowrap px-6 py-4 font-semibold text-slate-900">{{ $managedUser->name }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ $managedUser->email }}</td>
                                    <td class="whitespace-nowrap px-6 py-4 text-slate-500">{{ ucfirst($managedUser->role) }}</td>
                                    <td class="whitespace-nowrap px-6 py-4">
                                        <span class="app-badge {{ $managedUser->is_active ? 'bg-emerald-50 text-emerald-700' : 'bg-slate-100 text-slate-600' }}">{{ $managedUser->is_active ? 'Active' : 'Disabled' }}</span>
                                    </td>
                                    <td class="whitespace-nowrap px-6 py-4 text-right">
                                        <div class="inline-flex items-center gap-3">
                                            <form method="POST" action="{{ route('users.update_status', $managedUser) }}">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="is_active" value="{{ $managedUser->is_active ? 0 : 1 }}">
                                                <button type="submit" class="text-sm font-semibold text-indigo-600 hover:text-indigo-700">
                                                    {{ $managedUser->is_active ? 'Disable' : 'Enable' }}
                                                </button>
                                            </form>
                                            <form method="POST" action="{{ route('users.destroy', $managedUser) }}" onsubmit="return confirm('Remove this account?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm font-semibold text-rose-600 hover:text-rose-700">Remove</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </section>
        @endif
    </div>
</x-app-layout>
