@extends('layouts.admin')
@section('title', 'لوحة التحكم')

@push('styles')
<style>
    .stat-card { border: none; border-radius: 16px; transition: transform .2s; }
    .stat-card:hover { transform: translateY(-4px); }
    .stat-icon { width: 56px; height: 56px; border-radius: 14px;
        display: flex; align-items: center; justify-content: center; font-size: 1.5rem; }
    .chart-card { border: none; border-radius: 16px; box-shadow: 0 4px 20px rgba(0,0,0,.07); }
</style>
@endpush

@section('content')

{{-- ══ رأس الصفحة ══════════════════════════════════════ --}}
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h3 class="fw-bold mb-0">مرحباً، {{ auth()->user()->name }} 👋</h3>
        <small class="text-muted">{{ now()->format('l، d F Y') }}</small>
    </div>
    <span class="badge bg-success px-3 py-2 fs-6">
        <i class="bi bi-circle-fill me-1" style="font-size:.5rem"></i>
        {{ $stats['today_orders'] }} طلب اليوم
    </span>
</div>

{{-- ══ الإحصائيات السريعة ══════════════════════════════ --}}
<div class="row g-3 mb-4">
    @php
    $cards = [
        ['label'=>'إجمالي الإيرادات', 'val'=>'$'.number_format($stats['revenue'],2),
         'icon'=>'bi-cash-stack',    'color'=>'success',  'sub'=>'$'.number_format($stats['today_revenue'],2).' اليوم'],
        ['label'=>'الطلبات',          'val'=>$stats['orders'],
         'icon'=>'bi-receipt',       'color'=>'primary',  'sub'=>$stats['pending_orders'].' قيد الانتظار'],
        ['label'=>'المنتجات',         'val'=>$stats['products'],
         'icon'=>'bi-box-seam',      'color'=>'warning',  'sub'=>'منتج في المتجر'],
        ['label'=>'العملاء',          'val'=>$stats['customers'],
         'icon'=>'bi-people',        'color'=>'info',     'sub'=>'مستخدم مسجّل'],
    ];
    @endphp

    @foreach($cards as $card)
    <div class="col-xl-3 col-sm-6">
        <div class="card stat-card shadow-sm">
            <div class="card-body d-flex align-items-center gap-3 p-4">
                <div class="stat-icon bg-{{ $card['color'] }} bg-opacity-15">
                    <i class="bi {{ $card['icon'] }} text-{{ $card['color'] }}"></i>
                </div>
                <div>
                    <div class="text-muted small">{{ $card['label'] }}</div>
                    <div class="fw-bold fs-4 lh-1 my-1">{{ $card['val'] }}</div>
                    <div class="text-muted" style="font-size:.75rem">{{ $card['sub'] }}</div>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>

{{-- ══ Chart 1: مبيعات آخر 30 يوم ══════════════════════ --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-body p-4">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h6 class="fw-bold mb-0">📈 المبيعات — آخر 30 يوم</h6>
                    <div class="d-flex gap-3">
                        <small class="text-muted">
                            <span class="d-inline-block rounded-circle me-1"
                                  style="width:10px;height:10px;background:#0d6efd"></span>
                            الإيرادات
                        </small>
                        <small class="text-muted">
                            <span class="d-inline-block rounded-circle me-1"
                                  style="width:10px;height:10px;background:#20c997"></span>
                            عدد الطلبات
                        </small>
                    </div>
                </div>
                <canvas id="salesChart" height="90"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ══ Chart 2 + 3 ═════════════════════════════════════ --}}
<div class="row g-4 mb-4">

    {{-- أكثر المنتجات مبيعاً --}}
    <div class="col-lg-7">
        <div class="card chart-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">🏆 أكثر المنتجات مبيعاً</h6>
                @if(count($topProductLabels) > 0)
                    <canvas id="topProductsChart" height="220"></canvas>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-bar-chart" style="font-size:3rem;opacity:.3"></i>
                        <p class="mt-2">لا توجد بيانات مبيعات بعد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- توزيع الطلبات --}}
    <div class="col-lg-5">
        <div class="card chart-card h-100">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📊 توزيع حالات الطلبات</h6>
                @if($stats['orders'] > 0)
                    <canvas id="statusChart" height="220"></canvas>
                @else
                    <div class="text-center text-muted py-5">
                        <i class="bi bi-pie-chart" style="font-size:3rem;opacity:.3"></i>
                        <p class="mt-2">لا توجد طلبات بعد</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- ══ Chart 4: المبيعات الشهرية ═══════════════════════ --}}
<div class="row g-4 mb-4">
    <div class="col-12">
        <div class="card chart-card">
            <div class="card-body p-4">
                <h6 class="fw-bold mb-3">📅 المبيعات الشهرية — آخر 6 أشهر</h6>
                <canvas id="monthlyChart" height="80"></canvas>
            </div>
        </div>
    </div>
</div>

{{-- ══ آخر الطلبات ══════════════════════════════════════ --}}
<div class="card chart-card">
    <div class="card-body p-4">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h6 class="fw-bold mb-0">🕐 آخر الطلبات</h6>
            <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-dark">عرض الكل</a>
        </div>
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th><th>العميل</th><th>المبلغ</th>
                        <th>الخصم</th><th>الحالة</th><th>التاريخ</th><th></th>
                    </tr>
                </thead>
                <tbody>
                @forelse($recent_orders as $order)
                <tr>
                    <td><span class="badge bg-light text-dark border">#{{ $order->id }}</span></td>
                    <td>
                        <div class="fw-bold">{{ $order->customer_name }}</div>
                        <small class="text-muted">{{ $order->customer_email }}</small>
                    </td>
                    <td class="fw-bold text-success">${{ number_format($order->total, 2) }}</td>
                    <td>
                        @if($order->discount > 0)
                            <span class="badge bg-warning text-dark">
                                -${{ number_format($order->discount, 2) }}
                            </span>
                        @else
                            <span class="text-muted">—</span>
                        @endif
                    </td>
                    <td>
                        @php $colors=['pending'=>'warning','processing'=>'info','shipped'=>'primary','delivered'=>'success','cancelled'=>'danger']; @endphp
                        <span class="badge bg-{{ $colors[$order->status] ?? 'secondary' }}">
                            {{ $order->status_label }}
                        </span>
                    </td>
                    <td class="text-muted small">{{ $order->created_at->diffForHumans() }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}"
                           class="btn btn-sm btn-outline-dark">عرض</a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center text-muted py-4">لا توجد طلبات حتى الآن</td>
                </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
Chart.defaults.font.family = 'system-ui, sans-serif';
Chart.defaults.color = '#6c757d';

// ── بيانات من PHP ──────────────────────────────────────
const salesLabels       = @json($salesLabels);
const salesTotals       = @json($salesTotals);
const salesCounts       = @json($salesCounts);
const topProductLabels  = @json($topProductLabels);
const topProductData    = @json($topProductData);
const statusLabels      = @json($statusLabels);
const statusCounts      = @json($statusCounts);
const statusColors      = @json($statusColors);
const monthlyLabels     = @json($monthlyLabels);
const monthlyTotals     = @json($monthlyTotals);

// ── Chart 1: مبيعات آخر 30 يوم ────────────────────────
new Chart(document.getElementById('salesChart'), {
    type: 'line',
    data: {
        labels: salesLabels,
        datasets: [
            {
                label: 'الإيرادات ($)',
                data: salesTotals,
                borderColor: '#0d6efd',
                backgroundColor: 'rgba(13,110,253,.08)',
                borderWidth: 2.5,
                fill: true,
                tension: 0.4,
                pointRadius: 3,
                pointHoverRadius: 6,
                yAxisID: 'y',
            },
            {
                label: 'عدد الطلبات',
                data: salesCounts,
                borderColor: '#20c997',
                backgroundColor: 'transparent',
                borderWidth: 2,
                borderDash: [5, 5],
                tension: 0.4,
                pointRadius: 3,
                yAxisID: 'y1',
            }
        ]
    },
    options: {
        responsive: true,
        interaction: { mode: 'index', intersect: false },
        plugins: { legend: { display: false } },
        scales: {
            y:  { position: 'right', grid: { color: '#f0f0f0' },
                  ticks: { callback: v => '$' + v.toLocaleString() } },
            y1: { position: 'left',  grid: { drawOnChartArea: false },
                  ticks: { stepSize: 1 } },
            x:  { grid: { display: false } }
        }
    }
});

// ── Chart 2: أكثر المنتجات مبيعاً ─────────────────────
if (topProductLabels.length > 0) {
    const barColors = [
        '#0d6efd','#6610f2','#6f42c1','#d63384',
        '#dc3545','#fd7e14','#ffc107'
    ];
    new Chart(document.getElementById('topProductsChart'), {
        type: 'bar',
        data: {
            labels: topProductLabels,
            datasets: [{
                label: 'الكمية المباعة',
                data: topProductData,
                backgroundColor: barColors.slice(0, topProductData.length),
                borderRadius: 8,
                borderSkipped: false,
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#f0f0f0' }, ticks: { stepSize: 1 } },
                x: { grid: { display: false },
                     ticks: { maxRotation: 30,
                              callback: function(val, i) {
                                  const lbl = this.getLabelForValue(val);
                                  return lbl.length > 15 ? lbl.slice(0, 15) + '…' : lbl;
                              }}}
            }
        }
    });
}

// ── Chart 3: توزيع حالات الطلبات ──────────────────────
if ({{ $stats['orders'] }} > 0) {
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusCounts,
                backgroundColor: statusColors,
                borderWidth: 0,
                hoverOffset: 8,
            }]
        },
        options: {
            responsive: true,
            cutout: '65%',
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: { padding: 16, usePointStyle: true, pointStyleWidth: 10 }
                }
            }
        }
    });
}

// ── Chart 4: المبيعات الشهرية ──────────────────────────
new Chart(document.getElementById('monthlyChart'), {
    type: 'bar',
    data: {
        labels: monthlyLabels,
        datasets: [{
            label: 'الإيرادات ($)',
            data: monthlyTotals,
            backgroundColor: 'rgba(13,110,253,.15)',
            borderColor: '#0d6efd',
            borderWidth: 2,
            borderRadius: 10,
            borderSkipped: false,
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { grid: { color: '#f0f0f0' },
                 ticks: { callback: v => '$' + v.toLocaleString() } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush
