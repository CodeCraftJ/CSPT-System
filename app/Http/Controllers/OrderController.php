<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $this->authorizeStaff($request);

        $orders = Order::with('user', 'orderItems.foodItem')
            ->orderByRaw("CASE WHEN status = 'pending' THEN 0 ELSE 1 END")
            ->orderByDesc('created_at')
            ->get();

        return view('orders.index', [
            'orders' => $orders,
            'isStaff' => true,
        ]);
    }

    public function create(Request $request)
    {
        $this->authorizeStaff($request);

        return view('orders.create', [
            'foodItems' => FoodItem::orderBy('name')->get(),
            'selectedOrder' => null,
        ]);
    }

    public function store(Request $request)
    {
        $this->authorizeStaff($request);

        $validated = $this->validateOrderRequest($request);
        $items = $this->prepareItems($validated['items']);

        if ($items->isEmpty()) {
            return Redirect::back()->withInput()->withErrors(['items' => 'Please select at least one product and quantity.']);
        }

        $order = null;

        DB::beginTransaction();
        try {
            $this->consumeStockIfCompleted($items, $validated['status']);

            $order = Order::create([
                'user_id' => $request->user()->id,
                'total_price' => $this->calculateTotal($items),
                'status' => $validated['status'],
            ]);

            $this->syncOrderItems($order, $items);

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return Redirect::back()
                ->withInput()
                ->withErrors(['general' => $exception->getMessage() ?: 'Unable to place order. Please try again.']);
        }

        return Redirect::route('orders.show', $order)->with('success', 'Order submitted successfully.');
    }

    public function show(Request $request, Order $order)
    {
        $this->authorizeStaff($request);

        $order->load('user', 'orderItems.foodItem');

        return view('orders.show', [
            'order' => $order,
            'isStaff' => true,
        ]);
    }

    public function edit(Request $request, Order $order)
    {
        $this->authorizeStaff($request);

        $order->load('orderItems.foodItem');

        return view('orders.edit', [
            'order' => $order,
            'foodItems' => FoodItem::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Order $order)
    {
        $this->authorizeStaff($request);

        $validated = $this->validateOrderRequest($request);
        $items = $this->prepareItems($validated['items']);

        if ($items->isEmpty()) {
            return Redirect::back()->withInput()->withErrors(['items' => 'Please select at least one product and quantity.']);
        }

        DB::beginTransaction();
        try {
            $order = Order::with('orderItems.foodItem')->lockForUpdate()->findOrFail($order->id);
            $this->applyOrderChanges($order, $items, $validated['status']);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return Redirect::back()
                ->withInput()
                ->withErrors(['general' => $exception->getMessage() ?: 'Unable to update the order. Please try again.']);
        }

        return Redirect::route('orders.show', $order)->with('success', 'Order updated successfully.');
    }

    public function destroy(Request $request, Order $order)
    {
        $this->authorizeStaff($request);

        DB::beginTransaction();
        try {
            $order = Order::with('orderItems.foodItem')->lockForUpdate()->findOrFail($order->id);

            if ($order->status === 'completed') {
                $this->restoreStockForOrder($order->orderItems);
            }

            $order->orderItems()->delete();
            $order->delete();

            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return Redirect::back()->withErrors(['general' => 'Unable to delete the order. Please try again.']);
        }

        return Redirect::route('orders.index')->with('success', 'Order deleted successfully.');
    }

    public function updateStatus(Request $request, Order $order)
    {
        $this->authorizeStaff($request);

        $validated = $request->validate([
            'status' => ['required', 'in:pending,completed'],
        ]);

        DB::beginTransaction();
        try {
            $order = Order::with('orderItems.foodItem')->lockForUpdate()->findOrFail($order->id);
            $this->applyStatusTransition($order, $validated['status']);
            DB::commit();
        } catch (\Throwable $exception) {
            DB::rollBack();

            return Redirect::back()->withErrors(['status' => 'Unable to update order status. Please try again.']);
        }

        return Redirect::route('orders.index')->with('success', 'Order status has been updated.');
    }

    public function export(Request $request)
    {
        $this->authorizeStaff($request);

        $orders = Order::with('user', 'orderItems.foodItem')->orderBy('created_at', 'desc')->get();
        $filename = 'canteen-orders.csv';

        $callback = function () use ($orders) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Order ID', 'Entered By', 'Item', 'Quantity', 'Unit Price', 'Total Price', 'Status', 'Ordered At']);

            foreach ($orders as $order) {
                foreach ($order->orderItems as $item) {
                    fputcsv($handle, [
                        $order->id,
                        $order->user->name,
                        $item->foodItem->name,
                        $item->quantity,
                        number_format($item->unit_price, 2),
                        number_format($item->total_price, 2),
                        $order->status,
                        $order->created_at->toDateTimeString(),
                    ]);
                }
            }

            fclose($handle);
        };

        return Response::streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    private function validateOrderRequest(Request $request): array
    {
        return $request->validate([
            'status' => ['required', 'in:pending,completed'],
            'items' => ['required', 'array', 'min:1'],
            'items.*.food_item_id' => ['required', 'exists:food_items,id'],
            'items.*.quantity' => ['required', 'integer', 'min:1'],
        ]);
    }

    private function prepareItems(array $items): Collection
    {
        $foodItemIds = collect($items)->pluck('food_item_id')->all();
        $foodItems = FoodItem::whereIn('id', $foodItemIds)->get()->keyBy('id');

        return collect($items)
            ->map(function (array $item) use ($foodItems) {
                $foodItem = $foodItems->get($item['food_item_id']);

                if (! $foodItem) {
                    return null;
                }

                $quantity = (int) $item['quantity'];

                return [
                    'food_item_id' => $foodItem->id,
                    'quantity' => $quantity,
                    'unit_price' => (float) $foodItem->price,
                    'subtotal' => (float) $foodItem->price * $quantity,
                ];
            })
            ->filter(fn ($item) => $item !== null && $item['quantity'] > 0)
            ->groupBy('food_item_id')
            ->map(function (Collection $group) {
                $first = $group->first();

                return [
                    'food_item_id' => $first['food_item_id'],
                    'quantity' => (int) $group->sum('quantity'),
                    'unit_price' => (float) $first['unit_price'],
                    'subtotal' => (float) $group->sum('subtotal'),
                ];
            })
            ->values();
    }

    private function calculateTotal(Collection $items): float
    {
        return (float) $items->sum('subtotal');
    }

    private function syncOrderItems(Order $order, Collection $items): void
    {
        foreach ($items as $item) {
            $order->orderItems()->create([
                'food_item_id' => $item['food_item_id'],
                'quantity' => $item['quantity'],
                'unit_price' => $item['unit_price'],
                'total_price' => $item['subtotal'],
            ]);
        }
    }

    private function applyOrderChanges(Order $order, Collection $items, string $status): void
    {
        $currentCompleted = $order->status === 'completed';
        $targetCompleted = $status === 'completed';
        $currentItems = $order->orderItems->keyBy('food_item_id');
        $newItems = $items->keyBy('food_item_id');

        if ($currentCompleted && $targetCompleted) {
            $this->applyCompletedOrderDelta($currentItems, $newItems);
        } elseif ($currentCompleted && ! $targetCompleted) {
            $this->restoreStockForOrder($order->orderItems);
        } elseif (! $currentCompleted && $targetCompleted) {
            $this->consumeStockIfCompleted($items, $status);
        }

        $order->update([
            'total_price' => $this->calculateTotal($items),
            'status' => $status,
        ]);

        $order->orderItems()->delete();
        $this->syncOrderItems($order, $items);
    }

    private function applyCompletedOrderDelta(Collection $currentItems, Collection $newItems): void
    {
        $foodItemIds = $currentItems->keys()
            ->merge($newItems->keys())
            ->unique()
            ->values();

        $foodItems = FoodItem::whereIn('id', $foodItemIds)->lockForUpdate()->get()->keyBy('id');

        foreach ($foodItemIds as $foodItemId) {
            $currentQuantity = (int) optional($currentItems->get($foodItemId))->quantity;
            $newQuantity = (int) optional($newItems->get($foodItemId))->quantity;
            $difference = $newQuantity - $currentQuantity;

            if ($difference > 0) {
                $foodItem = $foodItems->get($foodItemId);

                if (! $foodItem || $foodItem->quantity < $difference) {
                    throw new \RuntimeException('Not enough stock available to update this order.');
                }

                $foodItem->decrement('quantity', $difference);
            } elseif ($difference < 0) {
                $foodItems->get($foodItemId)?->increment('quantity', abs($difference));
            }
        }
    }

    private function consumeStockIfCompleted(Collection $items, string $status): void
    {
        if ($status !== 'completed') {
            return;
        }

        $foodItems = FoodItem::whereIn('id', $items->pluck('food_item_id')->all())->lockForUpdate()->get()->keyBy('id');

        foreach ($items as $item) {
            $foodItem = $foodItems->get($item['food_item_id']);

            if (! $foodItem || $foodItem->quantity < $item['quantity']) {
                throw new \RuntimeException('Not enough stock available for the selected product.');
            }

            $foodItem->decrement('quantity', $item['quantity']);
        }
    }

    private function restoreStockForOrder(Collection $orderItems): void
    {
        $foodItems = FoodItem::whereIn('id', $orderItems->pluck('food_item_id')->all())->lockForUpdate()->get()->keyBy('id');

        foreach ($orderItems as $orderItem) {
            $foodItems->get($orderItem->food_item_id)?->increment('quantity', $orderItem->quantity);
        }
    }

    private function applyStatusTransition(Order $order, string $status): void
    {
        if ($order->status === $status) {
            return;
        }

        if ($order->status !== 'completed' && $status === 'completed') {
            $this->consumeStockIfCompleted(
                $order->orderItems->map(fn ($item) => [
                    'food_item_id' => $item->food_item_id,
                    'quantity' => $item->quantity,
                ]),
                $status
            );
        }

        if ($order->status === 'completed' && $status === 'pending') {
            $this->restoreStockForOrder($order->orderItems);
        }

        $order->update(['status' => $status]);
    }

    private function authorizeStaff(Request $request): void
    {
        if (! in_array($request->user()->role, ['admin', 'staff'], true)) {
            abort(403);
        }
    }
}
