<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;

class DashboardController extends Controller {
    public function index() {
        $stats = [
            'products'        => Product::count(),
            'orders'          => Order::count(),
            'revenue'         => Order::where('status', '!=', 'cancelled')->sum('total'),
            'pending_orders'  => Order::where('status', 'pending')->count(),
        ];
        $recent_orders = Order::latest()->take(5)->get();
        return view('admin.dashboard', compact('stats', 'recent_orders'));
    }
}
