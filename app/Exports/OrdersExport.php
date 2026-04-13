<?php
namespace App\Exports;

use App\Models\Order;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class OrdersExport implements
    FromCollection,
    WithHeadings,
    WithMapping,
    WithStyles,
    ShouldAutoSize
{
    public function collection() {
        return Order::with('items', 'user')->latest()->get();
    }

    public function headings(): array {
        return [
            '#',
            'اسم العميل',
            'البريد الإلكتروني',
            'العنوان',
            'المنتجات',
            'المجموع الفرعي',
            'الخصم',
            'كوبون',
            'الإجمالي',
            'الحالة',
            'تاريخ الطلب',
        ];
    }

    public function map($order): array {
        return [
            $order->id,
            $order->customer_name,
            $order->customer_email,
            $order->customer_address,
            $order->items->map(fn($i) => $i->product_name . ' ×' . $i->quantity)->join(' | '),
            '$' . number_format($order->total + $order->discount, 2),
            '$' . number_format($order->discount, 2),
            $order->coupon_code ?? '—',
            '$' . number_format($order->total, 2),
            $order->status_label,
            $order->created_at->format('Y-m-d H:i'),
        ];
    }

    public function styles(Worksheet $sheet): array {
        return [
            1 => [
                'font'      => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill'      => ['fillType' => 'solid', 'startColor' => ['rgb' => '1a1a2e']],
                'alignment' => ['horizontal' => 'center'],
            ],
        ];
    }
}
