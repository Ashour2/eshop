<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <title>طلب جديد</title>
    <style>
        * { margin:0; padding:0; box-sizing:border-box; }
        body { font-family:'Segoe UI',Tahoma,Arial,sans-serif;
               background:#f4f6f9; color:#333; direction:rtl; }
        .wrapper { max-width:620px; margin:30px auto; background:#fff;
                   border-radius:16px; overflow:hidden;
                   box-shadow:0 4px 24px rgba(0,0,0,.08); }
        .header { background:linear-gradient(135deg,#198754,#20c997);
                  padding:36px 32px; text-align:center; color:#fff; }
        .header .icon { font-size:3rem; margin-bottom:10px; }
        .header h1 { font-size:1.5rem; font-weight:700; }
        .body { padding:32px; }
        .alert-box { background:#d1fae5; border:1px solid #6ee7b7;
                     border-radius:12px; padding:16px 20px;
                     margin-bottom:24px; color:#065f46; font-weight:600; }
        .info-box { background:#f8f9ff; border-radius:12px;
                    padding:20px; margin-bottom:20px;
                    border:1px solid #e8ecff; }
        .info-box h3 { color:#0d6efd; font-size:.9rem;
                       margin-bottom:12px; letter-spacing:.5px; }
        .info-row { display:flex; justify-content:space-between;
                    padding:7px 0; border-bottom:1px solid #eee; font-size:.9rem; }
        .info-row:last-child { border-bottom:none; }
        .info-row .label { color:#666; }
        .info-row .value { font-weight:600; }
        .products-table { width:100%; border-collapse:collapse; margin-bottom:20px; }
        .products-table th { background:#1a1a2e; color:#fff;
                              padding:10px 14px; font-size:.83rem; text-align:right; }
        .products-table td { padding:10px 14px; border-bottom:1px solid #f0f0f0;
                              font-size:.88rem; }
        .grand-total { background:#1a1a2e; color:#fff; border-radius:10px;
                       padding:16px 20px; display:flex;
                       justify-content:space-between; font-size:1.1rem;
                       font-weight:700; margin-bottom:24px; }
        .grand-total .amount { color:#ffc107; }
        .cta-btn { display:block; background:#198754; color:#fff !important;
                   text-align:center; padding:14px; border-radius:10px;
                   text-decoration:none; font-weight:700; margin-bottom:20px; }
        .footer { background:#1a1a2e; color:rgba(255,255,255,.6);
                  text-align:center; padding:20px; font-size:.82rem; }
    </style>
</head>
<body>
<div class="wrapper">

    <div class="header">
        <div class="icon">🛒</div>
        <h1>طلب جديد وصل!</h1>
    </div>

    <div class="body">
        <div class="alert-box">
            🎉 تم استلام طلب جديد برقم #{{ $order->id }} — يحتاج مراجعتك
        </div>

        {{-- بيانات العميل --}}
        <div class="info-box">
            <h3>👤 بيانات العميل</h3>
            <div class="info-row">
                <span class="label">الاسم</span>
                <span class="value">{{ $order->customer_name }}</span>
            </div>
            <div class="info-row">
                <span class="label">البريد</span>
                <span class="value">{{ $order->customer_email }}</span>
            </div>
            <div class="info-row">
                <span class="label">العنوان</span>
                <span class="value">{{ $order->customer_address }}</span>
            </div>
            <div class="info-row">
                <span class="label">وقت الطلب</span>
                <span class="value">{{ $order->created_at->format('Y-m-d H:i') }}</span>
            </div>
        </div>

        {{-- المنتجات --}}
        <h3 style="margin-bottom:12px;font-size:1rem">📦 المنتجات المطلوبة</h3>
        <table class="products-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th style="text-align:center">الكمية</th>
                    <th style="text-align:left">الإجمالي</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td>{{ $item->product_name }}</td>
                    <td style="text-align:center">{{ $item->quantity }}</td>
                    <td style="text-align:left">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- الإجمالي --}}
        <div class="grand-total">
            <span>
                @if($order->discount > 0)
                    بعد خصم {{ $order->coupon_code }}
                @else
                    الإجمالي النهائي
                @endif
            </span>
            <span class="amount">${{ number_format($order->total, 2) }}</span>
        </div>

        <a href="{{ url('/admin/orders/' . $order->id) }}" class="cta-btn">
            ⚙️ إدارة الطلب في لوحة التحكم
        </a>
    </div>

    <div class="footer">
        <p>© {{ date('Y') }} متجرنا الإلكتروني — إشعار داخلي للأدمن</p>
    </div>

</div>
</body>
</html>
