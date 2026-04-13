<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Exports\OrdersExport;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    public function index() {
        $orders = Order::latest()->paginate(20);
        return view('admin.orders.index', compact('orders'));
    }

    public function show(Order $order) {
        $order->load('items');
        return view('admin.orders.show', compact('order'));
    }

    public function updateStatus(Request $request, Order $order) {
        $request->validate([
            'status' => 'required|in:pending,processing,shipped,delivered,cancelled',
        ]);
        $order->update(['status' => $request->status]);
        return redirect()->back()->with('success', 'تم تحديث حالة الطلب');
    }

    // ── تصدير Excel ───────────────────────────────────────
    public function exportExcel() {
        return Excel::download(
            new OrdersExport,
            'orders-' . now()->format('Y-m-d') . '.xlsx'
        );
    }

    // ── تصدير PDF ─────────────────────────────────────────
    public function exportPdf() {
        $orders = Order::with('items')->latest()->get();

        $pdf = Pdf::loadView('admin.orders.pdf', compact('orders'))
                  ->setPaper('a4', 'landscape');

        return $pdf->download('orders-' . now()->format('Y-m-d') . '.pdf');
    }
}
