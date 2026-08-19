<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">Orders</div>
                <h2 class="page-title">Edit Order #{{ $order->id }}</h2>
                <p class="page-subtitle">Update products, quantities, totals, or order status without changing the dashboard layout.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl">
        @include('orders.partials.form', [
            'action' => route('orders.update', $order),
            'method' => 'PUT',
            'title' => 'Edit order',
            'subtitle' => 'Adjust the selected products and quantities, then save the updated order.',
            'submitLabel' => 'Update Order',
            'backRoute' => route('orders.show', $order),
            'foodItems' => $foodItems,
            'existingItems' => $order->orderItems->map(fn ($item) => [
                'food_item_id' => $item->food_item_id,
                'quantity' => $item->quantity,
            ])->all(),
            'order' => $order,
        ])
    </div>
</x-app-layout>
