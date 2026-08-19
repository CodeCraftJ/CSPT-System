<?php

namespace App\Http\Controllers;

use App\Models\FoodItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        if (! in_array($user->role, ['admin', 'staff'], true)) {
            abort(403);
        }

        $foodItems = FoodItem::orderBy('name')->get();
        $totalItems = $foodItems->count();
        $totalOrders = Order::count();
        $totalUsers = User::whereIn('role', ['admin', 'staff'])->count();
        $completedOrderItems = OrderItem::whereHas('order', function ($query) {
            $query->where('status', 'completed');
        });
        $totalRevenue = (clone $completedOrderItems)->sum('total_price');
        $totalProfit = (clone $completedOrderItems)
            ->join('food_items', 'order_items.food_item_id', 'food_items.id')
            ->selectRaw('COALESCE(SUM(order_items.quantity * (order_items.unit_price - food_items.cost)), 0) as profit')
            ->value('profit') ?? 0;
        $totalProfit = (float) $totalProfit;
        $lowStockItems = FoodItem::where('quantity', '<', 5)->orderBy('quantity')->get();
        $soldByType = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('food_items', 'order_items.food_item_id', '=', 'food_items.id')
            ->where('orders.status', 'completed')
            ->groupByRaw("COALESCE(food_items.snack_type, food_items.category, 'Unspecified')")
            ->orderByDesc(DB::raw('SUM(order_items.quantity)'))
            ->selectRaw("COALESCE(food_items.snack_type, food_items.category, 'Unspecified') as type")
            ->selectRaw('SUM(order_items.quantity) as quantity_sold')
            ->selectRaw('SUM(order_items.total_price) as total_sold')
            ->selectRaw('SUM(order_items.quantity * (order_items.unit_price - food_items.cost)) as profit')
            ->get();

        $pendingOrders = Order::with('user', 'orderItems.foodItem')
            ->where('status', 'pending')
            ->orderBy('created_at')
            ->take(8)
            ->get();
        $managedUsers = $user->role === 'admin'
            ? User::whereIn('role', ['staff'])->orderBy('role')->orderBy('name')->get()
            : collect();

        $availableItems = FoodItem::where('quantity', '>', 0)->orderBy('name')->get();
        $now = Carbon::now();
        $totalProductsSold = (clone $completedOrderItems)->sum('quantity');

        $dailySales = Order::where('status', 'completed')
            ->whereDate('created_at', $now->toDateString())
            ->sum('total_price');

        $monthlySales = Order::where('status', 'completed')
            ->whereYear('created_at', $now->year)
            ->whereMonth('created_at', $now->month)
            ->sum('total_price');

        $yearlySales = Order::where('status', 'completed')
            ->whereYear('created_at', $now->year)
            ->sum('total_price');

        $dailyProfit = (clone $completedOrderItems)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('food_items', 'order_items.food_item_id', '=', 'food_items.id')
            ->whereDate('orders.created_at', $now->toDateString())
            ->selectRaw('COALESCE(SUM(order_items.quantity * (order_items.unit_price - food_items.cost)), 0) as profit')
            ->value('profit') ?? 0;

        $monthlyProfit = (clone $completedOrderItems)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('food_items', 'order_items.food_item_id', '=', 'food_items.id')
            ->whereYear('orders.created_at', $now->year)
            ->whereMonth('orders.created_at', $now->month)
            ->selectRaw('COALESCE(SUM(order_items.quantity * (order_items.unit_price - food_items.cost)), 0) as profit')
            ->value('profit') ?? 0;

        $yearlyProfit = (clone $completedOrderItems)
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->join('food_items', 'order_items.food_item_id', '=', 'food_items.id')
            ->whereYear('orders.created_at', $now->year)
            ->selectRaw('COALESCE(SUM(order_items.quantity * (order_items.unit_price - food_items.cost)), 0) as profit')
            ->value('profit') ?? 0;

        return view('dashboard', [
            'user' => $user,
            'foodItems' => $foodItems,
            'totalItems' => $totalItems,
            'totalOrders' => $totalOrders,
            'totalUsers' => $totalUsers,
            'totalRevenue' => $totalRevenue,
            'totalProfit' => $totalProfit,
            'totalProductsSold' => $totalProductsSold,
            'dailySales' => $dailySales,
            'monthlySales' => $monthlySales,
            'yearlySales' => $yearlySales,
            'dailyProfit' => $dailyProfit,
            'monthlyProfit' => $monthlyProfit,
            'yearlyProfit' => $yearlyProfit,
            'lowStockItems' => $lowStockItems,
            'soldByType' => $soldByType,
            'pendingOrders' => $pendingOrders,
            'managedUsers' => $managedUsers,
            'availableItems' => $availableItems,
        ]);
    }
}
