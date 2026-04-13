<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>تقرير الطلبات</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family: 'DejaVu Sans', Arial, sans-serif;
               direction:rtl; color:#333; font-size:12px; }
        .header { background:#1a1a2e; color:#fff; padding:20px;
                  text-align:center; margin-bottom:20px; }
        .header h1 { font-size:20px; margin-bottom:4px; }
        .header p  { opacity:.7; font-size:11px; }
        .stats { display:flex; gap:12px; margin:0 20px 20px; }
        .stat-box { flex:1; background:#f8f9ff; border:1px solid #e8ecff;
                    border-radius:8px; padding:12px; text-align:center; }
        .stat-box .num { font-size:18px; font-weight:bold; color:#0d6efd; }
        .stat-box .lbl { font-size:10px; color:#666; margin-top:2px; }
        table { width:100%; border-collapse:collapse; margin:0 0 20px; }
        thead tr { background:#1a1a2e; color:#fff; }
        thead th { padding:10px 8px; text-align:right; font-size:11px; }
        tbody tr:nth-child(even) { background:#f8f9ff; }
        tbody tr:hover { background:#e8ecff; }
        tbody td { padding:9px 8px; border-bottom:1px solid #eee; font-size:11px; }
        .badge { padding:3px 8px; border-radius:50px; font-size:10px; font-weight:bold; }
        .badge-pending    { background:#fff3cd; color:#856404; }
        .badge-processing { background:#cff4fc; color:#055160; }
        .badge-shipped    { background:#cfe2ff; color:#084298; }
        .badge-delivered  { background:#d1e7dd; color:#0a3622; }
        .badge-cancelled  { background:#f8d7da; color:#842029; }
        .footer { text-align:center; color:#999; font-size:10px;
                  border-top:1px solid #eee; padding-top:12px; margin:0 20px; }
        .page-title { margin:0 20px 12px; font-size:14px; font-weight:bold; color:#1a1a2e; }
        table { margin:0 20px; width:calc(100% - 40px); }
    </style>
</head>
<body>

<div class="header">
    <h1>🛒 تقرير الطلبات</h1>
    <p>تم الإنشاء في: {{ now()->format('Y-m-d H:i') }}</p>
</div>

{{-- إحصائيات --}}
<div class="stats">
    <div class="stat-box">
        <div class="num">{{ $orders->count() }}</div>
        <div class="lbl">إجمالي الطلبات</div>
    </div>
    <div class="stat-box">
        <div class="num">${{ number_format($orders->where('status','!=','cancelled')->sum('total'), 2) }}</div>
        <div class="lbl">إجمالي الإيرادات</div>
    </div>
    <div class="stat-box">
        <div class="num">{{ $orders->where('status','pending')->count() }}</div>
        <div class="lbl">قيد الانتظار</div>
    </div>
    <div class="stat-box">
        <div class="num">{{ $orders->where('status','delivered')->count() }}</div>
        <div class="lbl">تم التسليم</div>
    </div>
</div>

<p class="page-title">قائمة الطلبات</p>

<table>
    <thead>
        <tr>
            <th>#</th>
            <th>العميل</th>
            <th>المنتجات</th>
            <th>الخصم</th>
            <th>الإجمالي</th>
            <th>الحالة</th>
            <th>التاريخ</th>
        </tr>
    </thead>
    <tbody>
        @foreach($orders as $order)
        <tr>
            <td>{{ $order->id }}</td>
            <td>
                <strong>{{ $order->customer_name }}</strong><br>
                <span style="color:#666;font-size:10px">{{ $order->customer_email }}</span>
            </td>
            <td>
                @foreach($order->items as $item)
                    {{ $item->product_name }} ×{{ $item->quantity }}<br>
                @endforeach
            </td>
            <td>
                @if($order->discount > 0)
                    <span style="color:#e74c3c">-${{ number_format($order->discount, 2) }}</span>
                @else — @endif
            </td>
            <td><strong>${{ number_format($order->total, 2) }}</strong></td>
            <td>
                <span class="badge badge-{{ $order->status }}">
                    {{ $order->status_label }}
                </span>
            </td>
            <td>{{ $order->created_at->format('Y-m-d') }}</td>
        </tr>
        @endforeach
    </tbody>
</table>

<div class="footer">
    <p>© {{ date('Y') }} متجرنا الإلكتروني — تقرير سري للاستخدام الداخلي فقط</p>
</div>

</body>
</html>
