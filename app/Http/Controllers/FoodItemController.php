<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class FoodItemController extends Controller
{
    public function index()
    {
        $this->authorizeStaff();
        $items = FoodItem::orderBy('name')->get();

        return view('food_items.index', [
            'items' => $items,
        ]);
    }

    public function create()
    {
        $this->authorizeStaff();

        return view('food_items.create');
    }

    public function store(Request $request)
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'snack_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_url' => ['nullable', 'string', 'url', 'max:1000'],
            'image' => ['nullable', 'file', 'image', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('food_images', 'public');
            $data['image_url'] = Storage::url($path);
        }

        FoodItem::create($data);

        return Redirect::route('food_items.index')->with('success', 'Food item has been saved.');
    }

    public function edit(FoodItem $foodItem)
    {
        $this->authorizeStaff();

        return view('food_items.edit', [
            'foodItem' => $foodItem,
        ]);
    }

    public function update(Request $request, FoodItem $foodItem)
    {
        $this->authorizeStaff();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'category' => ['required', 'string', 'max:255'],
            'snack_type' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string', 'max:1000'],
            'image_url' => ['nullable', 'string', 'url', 'max:1000'],
            'image' => ['nullable', 'file', 'image', 'max:2048'],
            'price' => ['required', 'numeric', 'min:0'],
            'cost' => ['required', 'numeric', 'min:0'],
            'quantity' => ['required', 'integer', 'min:0'],
        ]);

        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('food_images', 'public');
            $data['image_url'] = Storage::url($path);
        }

        $foodItem->update($data);

        return Redirect::route('food_items.index')->with('success', 'Food item has been updated.');
    }

    public function destroy(FoodItem $foodItem)
    {
        $this->authorizeStaff();

        $foodItem->delete();

        return Redirect::route('food_items.index')->with('success', 'Food item has been deleted.');
    }

    public function export()
    {
        $this->authorizeStaff();

        $items = $this->buildSalesReportItems();
        $filename = 'canteen-food-items.csv';

        $callback = function () use ($items) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Product', 'Type', 'Cost Price', 'Selling Price', 'Stock', 'Sold', 'Remaining', 'Total Revenue', 'Profit', 'Status']);

            foreach ($items as $item) {
                fputcsv($handle, [
                    $item->product,
                    $item->type,
                    number_format((float) $item->cost_price, 2),
                    number_format((float) $item->selling_price, 2),
                    (int) $item->stock,
                    (int) $item->sold,
                    (int) $item->remaining,
                    number_format((float) $item->total_revenue, 2),
                    number_format((float) $item->profit, 2),
                    $item->status,
                ]);
            }

            fclose($handle);
        };

        return Response::streamDownload($callback, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    public function report()
    {
        $this->authorizeStaff();

        return view('food_items.report', [
            'items' => $this->buildSalesReportItems(),
        ]);
    }

    protected function authorizeStaff()
    {
        if (! auth()->user()->role || ! in_array(auth()->user()->role, ['admin', 'staff'], true)) {
            abort(403);
        }
    }

    private function buildSalesReportItems(): Collection
    {
        return FoodItem::query()
            ->leftJoin('order_items', 'food_items.id', '=', 'order_items.food_item_id')
            ->leftJoin('orders', function ($join) {
                $join->on('order_items.order_id', '=', 'orders.id')
                    ->where('orders.status', '=', 'completed');
            })
            ->groupBy(
                'food_items.id',
                'food_items.name',
                'food_items.category',
                'food_items.snack_type',
                'food_items.cost',
                'food_items.price',
                'food_items.quantity'
            )
            ->orderBy('food_items.name')
            ->select('food_items.name as product')
            ->selectRaw("COALESCE(food_items.snack_type, food_items.category) as type")
            ->selectRaw('food_items.cost as cost_price')
            ->selectRaw('food_items.price as selling_price')
            ->selectRaw('CAST(food_items.quantity + COALESCE(SUM(CASE WHEN orders.id IS NOT NULL THEN order_items.quantity ELSE 0 END), 0) AS UNSIGNED) as stock')
            ->selectRaw('COALESCE(SUM(CASE WHEN orders.id IS NOT NULL THEN order_items.quantity ELSE 0 END), 0) as sold')
            ->selectRaw('food_items.quantity as remaining')
            ->selectRaw('COALESCE(SUM(CASE WHEN orders.id IS NOT NULL THEN order_items.total_price ELSE 0 END), 0) as total_revenue')
            ->selectRaw('COALESCE(SUM(CASE WHEN orders.id IS NOT NULL THEN order_items.quantity * (order_items.unit_price - food_items.cost) ELSE 0 END), 0) as profit')
            ->selectRaw("CASE
                WHEN food_items.quantity <= 0 THEN 'Out of Stock'
                WHEN food_items.quantity <= 5 THEN 'Low Stock'
                ELSE 'In Stock'
            END as status")
            ->get();
    }
}
