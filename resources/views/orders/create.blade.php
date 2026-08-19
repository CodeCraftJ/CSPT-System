<x-app-layout>
    <x-slot name="header">
        <div class="page-header">
            <div>
                <div class="page-kicker">Orders</div>
                <h2 class="page-title">Create Order</h2>
                <p class="page-subtitle">Staff can enter customer orders directly and let the system compute subtotals and totals automatically.</p>
            </div>
        </div>
    </x-slot>

    <div class="mx-auto max-w-6xl">
        @include('orders.partials.form', [
            'action' => route('orders.store'),
            'method' => 'POST',
            'title' => 'Enter new order',
            'subtitle' => 'Select products, input quantities, and let the order total update automatically.',
            'submitLabel' => 'Save Order',
            'backRoute' => route('orders.index'),
            'foodItems' => $foodItems,
            'existingItems' => [],
        ])
    </div>
</x-app-layout>
