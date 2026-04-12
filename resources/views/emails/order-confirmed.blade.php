<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تأكيد الطلب</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Arial, sans-serif;
               background: #f4f6f9; color: #333; direction: rtl; }
        .wrapper { max-width: 620px; margin: 30px auto; background: #fff;
                   border-radius: 16px; overflow: hidden;
                   box-shadow: 0 4px 24px rgba(0,0,0,.08); }
        .header { background: linear-gradient(135deg, #1a1a2e, #0d6efd);
                  padding: 40px 32px; text-align: center; color: #fff; }
        .header .icon { font-size: 3.5rem; margin-bottom: 12px; }
        .header h1 { font-size: 1.6rem; font-weight: 700; margin-bottom: 6px; }
        .header p { opacity: .85; font-size: .95rem; }
        .body { padding: 32px; }
        .greeting { font-size: 1.1rem; margin-bottom: 20px; }
        .info-box { background: #f8f9ff; border-radius: 12px;
                    padding: 20px; margin-bottom: 24px; border: 1px solid #e8ecff; }
        .info-box h3 { color: #0d6efd; font-size: .95rem;
                       margin-bottom: 14px; text-transform: uppercase;
                       letter-spacing: .5px; }
        .info-row { display: flex; justify-content: space-between;
                    padding: 8px 0; border-bottom: 1px solid #eee; font-size: .9rem; }
        .info-row:last-child { border-bottom: none; }
        .info-row .label { color: #666; }
        .info-row .value { font-weight: 600; }
        .products-table { width: 100%; border-collapse: collapse; margin-bottom: 24px; }
        .products-table th { background: #1a1a2e; color: #fff;
                              padding: 12px 16px; font-size: .85rem;
                              font-weight: 600; text-align: right; }
        .products-table td { padding: 12px 16px; border-bottom: 1px solid #f0f0f0;
                              font-size: .9rem; }
        .products-table tr:last-child td { border-bottom: none; }
        .products-table .product-name { font-weight: 600; }
        .totals { background: #f8f9ff; border-radius: 12px; padding: 16px 20px; }
        .total-row { display: flex; justify-content: space-between;
                     padding: 6px 0; font-size: .9rem; }
        .total-row.discount { color: #e74c3c; }
        .total-row.grand { font-size: 1.1rem; font-weight: 700;
                           color: #0d6efd; border-top: 2px solid #e8ecff;
                           margin-top: 8px; padding-top: 12px; }
        .status-badge { display: inline-block; background: #fff3cd;
                        color: #856404; padding: 6px 16px; border-radius: 50px;
                        font-size: .85rem; font-weight: 600; margin: 16px 0; }
        .cta-btn { display: block; background: #0d6efd; color: #fff !important;
                   text-align: center; padding: 14px 32px; border-radius: 10px;
                   text-decoration: none; font-weight: 700; font-size: 1rem;
                   margin: 24px 0; }
        .footer { background: #1a1a2e; color: rgba(255,255,255,.6);
                  text-align: center; padding: 24px; font-size: .82rem; }
        .footer a { color: #ffc107; text-decoration: none; }
    </style>
</head>
<body>
<div class="wrapper">

    {{-- Header --}}
    <div class="header">
        <div class="icon">✅</div>
        <h1>تم تأكيد طلبك!</h1>
        <p>شكراً لك، سيتم معالجة طلبك في أقرب وقت</p>
    </div>

    {{-- Body --}}
    <div class="body">
        <p class="greeting">
            مرحباً <strong>{{ $order->customer_name }}</strong>،<br>
            يسعدنا إخبارك بأنه تم استلام طلبك بنجاح وهو الآن قيد المعالجة.
        </p>

        {{-- معلومات الطلب --}}
        <div class="info-box">
            <h3>📋 معلومات الطلب</h3>
            <div class="info-row">
                <span class="label">رقم الطلب</span>
                <span class="value">#{{ $order->id }}</span>
            </div>
            <div class="info-row">
                <span class="label">تاريخ الطلب</span>
                <span class="value">{{ $order->created_at->format('Y-m-d H:i') }}</span>
            </div>
            <div class="info-row">
                <span class="label">الحالة</span>
                <span class="value">
                    <span class="status-badge">{{ $order->status_label }}</span>
                </span>
            </div>
            <div class="info-row">
                <span class="label">عنوان التوصيل</span>
                <span class="value">{{ $order->customer_address }}</span>
            </div>
        </div>

        {{-- المنتجات --}}
        <h3 style="margin-bottom:12px;font-size:1rem">🛍️ المنتجات المطلوبة</h3>
        <table class="products-table">
            <thead>
                <tr>
                    <th>المنتج</th>
                    <th style="text-align:center">الكمية</th>
                    <th style="text-align:left">السعر</th>
                </tr>
            </thead>
            <tbody>
                @foreach($order->items as $item)
                <tr>
                    <td class="product-name">{{ $item->product_name }}</td>
                    <td style="text-align:center">{{ $item->quantity }}</td>
                    <td style="text-align:left">${{ number_format($item->price * $item->quantity, 2) }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>

        {{-- الإجماليات --}}
        <div class="totals">
            <div class="total-row">
                <span>المجموع الفرعي</span>
                <span>${{ number_format($order->total + $order->discount, 2) }}</span>
            </div>
            @if($order->discount > 0)
            <div class="total-row discount">
                <span>🎟️ خصم الكوبون ({{ $order->coupon_code }})</span>
                <span>-${{ number_format($order->discount, 2) }}</span>
            </div>
            @endif
            <div class="total-row grand">
                <span>الإجمالي النهائي</span>
                <span>${{ number_format($order->total, 2) }}</span>
            </div>
        </div>

        {{-- CTA --}}
        <a href="{{ url('/account/orders/' . $order->id) }}" class="cta-btn">
            📦 تتبع طلبك الآن
        </a>

        <p style="color:#666;font-size:.9rem;line-height:1.8">
            إذا كان لديك أي استفسار، لا تتردد في التواصل معنا.<br>
            نتطلع إلى خدمتك دائماً! ❤️
        </p>
    </div>

    {{-- Footer --}}
    <div class="footer">
        <p>© {{ date('Y') }} متجرنا الإلكتروني — جميع الحقوق محفوظة</p>
        <p style="margin-top:6px">
            هذا الإيميل أُرسل تلقائياً، يُرجى عدم الرد عليه.
        </p>
    </div>

</div>
</body>
</html>
