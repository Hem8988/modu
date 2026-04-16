@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<div class="d-flex flex-wrap justify-content-between align-items-center mb-4 gap-3">
    <div>
        <h2 class="fs-3 fw-bolder text-dark mb-0" style="letter-spacing: -1px;">ModuShade Strategic Hub</h2>
        <div class="text-secondary fw-semibold mt-1" style="font-size: 0.85rem;">Real-time site & revenue command center</div>
    </div>
</div>

<div class="row row-cols-2 row-cols-md-3 row-cols-lg-5 g-3 mb-4">
    <div class="col">
        <a href="{{ route('admin.leads.index') }}" class="card h-100 text-center text-decoration-none border-0 shadow-sm border-bottom border-primary border-3 transition-hover">
            <div class="card-body p-3">
                <div class="fs-3 mb-2">🎯</div>
                <div class="small fw-bolder text-dark text-uppercase">+ Record Lead</div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.appointments.index') }}" class="card h-100 text-center text-decoration-none border-0 shadow-sm border-bottom border-3 transition-hover" style="border-bottom-color: #8b5cf6 !important;">
            <div class="card-body p-3">
                <div class="fs-3 mb-2">📏</div>
                <div class="small fw-bolder text-dark text-uppercase">+ Schedule Visit</div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.quotations.index') }}" class="card h-100 text-center text-decoration-none border-0 shadow-sm border-bottom border-warning border-3 transition-hover">
            <div class="card-body p-3">
                <div class="fs-3 mb-2">📄</div>
                <div class="small fw-bolder text-dark text-uppercase">+ Create Quote</div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.invoices.index') }}" class="card h-100 text-center text-decoration-none border-0 shadow-sm border-bottom border-dark border-3 transition-hover">
            <div class="card-body p-3">
                <div class="fs-3 mb-2">🧾</div>
                <div class="small fw-bolder text-dark text-uppercase">+ Generate Inv</div>
            </div>
        </a>
    </div>
    <div class="col">
        <a href="{{ route('admin.payments.index') }}" class="card h-100 text-center text-decoration-none border-0 shadow-sm border-bottom border-success border-3 transition-hover">
            <div class="card-body p-3">
                <div class="fs-3 mb-2">💰</div>
                <div class="small fw-bolder text-dark text-uppercase">+ Record Pay</div>
            </div>
        </a>
    </div>
</div>

<style>
    .transition-hover { transition: transform 0.2s ease-in-out; }
    .transition-hover:hover { transform: translateY(-3px); }
    .stat-card-accent { border-left: 4px solid #0d6efd; }
    .stat-card-gold { border-left: 4px solid #ffc107; }
</style>

<div class="row mb-4 g-4">
    <!-- Strategic Hub Left -->
    <div class="col-12 col-xl-8">
        <div class="card h-100 border-0 shadow-sm text-white p-4" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);">
            <h3 class="fs-6 fw-bold mb-3 text-warning border-start border-warning border-3 ps-2 text-uppercase">MODUSHADE OPERATIONS INTELLIGENCE</h3>
            <div class="row g-3">
                <div class="col-12 col-md-6 small opacity-75">
                    @if($alerts['not_contacted'] > 0)
                    <div class="d-flex align-items-start gap-2 mb-2"><span>🔥 <strong>{{ $alerts['not_contacted'] }} Leads cooling</strong> (+$5k scope)</span></div>
                    @endif
                    @if($alerts['quotes_pending'] > 0)
                    <div class="d-flex align-items-start gap-2 mb-2"><span>💰 <strong>{{ $alerts['quotes_pending'] }} Proposals Pending</strong> (+$15k potential)</span></div>
                    @endif
                </div>
                <div class="col-12 col-md-6">
                    <div class="bg-white bg-opacity-10 p-3 rounded-3 small">
                        <div class="text-warning fw-bolder mb-1">STRATEGIC PULSE</div>
                        <div class="opacity-75">"Optimize conversion by prioritzing site measurements within 48 hours (+18% win-rate)."</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Right -->
    <div class="col-12 col-xl-4">
        <div class="row g-3">
            <div class="col-6 col-xl-12">
                <div class="card border-0 shadow-sm stat-card-accent p-3 h-100">
                    <div class="text-uppercase text-secondary small fw-bold mb-1" style="font-size: 0.7rem;">Project Pipeline</div>
                    <div class="fs-3 fw-bolder text-primary">${{ number_format($stats['total_pipeline'], 0) }}</div>
                </div>
            </div>
            <div class="col-6 col-xl-12">
                <div class="card border-0 shadow-sm stat-card-gold p-3 h-100">
                    <div class="text-uppercase text-secondary small fw-bold mb-1" style="font-size: 0.7rem;">Revenue (Month)</div>
                    <div class="fs-3 fw-bolder text-warning">${{ number_format($stats['revenue_month'], 0) }}</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row row-cols-2 row-cols-md-4 g-3 mb-4">
    <div class="col">
        <div class="card h-100 border-0 shadow-sm p-3 border-top border-warning border-3">
            <div class="text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.7rem;">REVENUE (MONTH)</div>
            <div class="fs-4 fw-bold text-warning">${{ number_format($stats['revenue_month'], 0) }}</div>
            <div class="small text-success fw-bold mt-1">+{{ $stats['growth_rate'] }}% Pulse</div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm p-3 border-top border-3" style="border-top-color: #8b5cf6 !important;">
            <div class="text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.7rem;">REVENUE FORECAST</div>
            <div class="fs-4 fw-bold" style="color: #8b5cf6;">${{ number_format($stats['revenue_forecast'], 0) }}</div>
            <div class="small text-secondary fw-bold mt-1">Active Pipeline</div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm p-3 border-top border-primary border-3">
            <div class="text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.7rem;">CONVERSION RATE</div>
            <div class="fs-4 fw-bold text-primary">{{ $stats['conversion_rate'] }}%</div>
            <div class="small text-secondary fw-bold mt-1">Site to Closed</div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm p-3 border-top border-success border-3">
            <div class="text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.7rem;">LEAD QUALITY</div>
            <div class="fs-4 fw-bold text-success">{{ $stats['avg_lead_score'] }}%</div>
            <div class="small text-secondary fw-bold mt-1">Precision Score</div>
        </div>
    </div>
</div>

<div class="row row-cols-1 row-cols-md-3 g-3 mb-4">
    <div class="col">
        <div class="card h-100 border-0 shadow-sm p-3 border-end border-danger border-4">
            <div class="text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.7rem;">URGENT FOLLOW-UPS</div>
            <div class="fs-4 fw-bold text-danger">{{ $stats['follow_ups_today'] }}</div>
            <div class="small text-danger fw-bold mt-1">Immediate Priority</div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm p-3">
            <div class="text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.7rem;">ACTIVE DEALS</div>
            <div class="fs-4 fw-bold text-dark">{{ $stats['active_leads'] }}</div>
            <div class="small text-secondary fw-bold mt-1">Live Projects</div>
        </div>
    </div>
    <div class="col">
        <div class="card h-100 border-0 shadow-sm p-3">
            <div class="text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.7rem;">AVG DEAL VALUE</div>
            <div class="fs-4 fw-bold text-dark">${{ number_format($stats['total_pipeline'] / max(1, $stats['total_leads']), 0) }}</div>
            <div class="small text-secondary fw-bold mt-1">Per Operation</div>
        </div>
    </div>
</div>

{{-- World-Class 8-Stage Funnel Registry --}}
<div class="card border-0 shadow-sm p-4 mb-4 overflow-hidden">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h3 class="fs-6 fw-bold text-secondary text-uppercase m-0">Operations Pipeline Velocity</h3>
            <p class="text-secondary small mb-0">Live flow from Enquiry to Acquisition</p>
        </div>
        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary-subtle p-2 px-3 rounded-pill fw-bold">Real-time Ops ⚡</span>
    </div>
    
    <div class="table-responsive border-0">
        <div class="d-flex flex-nowrap gap-3 pb-3" style="min-width: 800px;">
            @php
                $funnelOrder = [
                    'Enquiries'      => ['label' => 'Enquiry',   'color' => '#64748b'],
                    'New Lead'       => ['label' => 'New Lead',  'color' => '#0d6efd'],
                    'Contacted'      => ['label' => 'Contacted', 'color' => '#0dcaf0'],
                    'Appt Scheduled' => ['label' => 'Site Visit', 'color' => '#8b5cf6'],
                    'Negotiation'    => ['label' => 'Negotiate', 'color' => '#f59e0b'],
                    'Won'            => ['label' => 'Closed Won','color' => '#10b981'],
                    'Lost'           => ['label' => 'Lost',      'color' => '#ef4444'],
                ];
                $totalEnquiries = max(1, $sidebarCounts['Enquiries'] ?? 1);
            @endphp
            
            @foreach($funnelOrder as $key => $data)
                <div class="flex-grow-1 text-center">
                    <div class="card bg-light border-0 p-3 h-100 rounded-4 transition-hover">
                        <div class="text-uppercase fw-bold text-secondary mb-2" style="font-size: 0.65rem; letter-spacing: 0.5px;">{{ $data['label'] }}</div>
                        <div class="fs-4 fw-black text-dark mb-1">{{ $sidebarCounts[$key] ?? 0 }}</div>
                        
                        <div class="progress mb-2 bg-white rounded-pill" style="height: 6px;">
                            <div class="progress-bar rounded-pill" style="width: 100%; background-color: {{ $data['color'] }};"></div>
                        </div>
                        
                        <div class="fw-bold text-secondary" style="font-size: 0.6rem;">
                            {{ round((($sidebarCounts[$key] ?? 0) / $totalEnquiries) * 100, 0) }}% Conv.
                        </div>
                    </div>
                </div>
                @if(!$loop->last)
                    <div class="d-flex align-items-center text-secondary opacity-25 px-1">
                        <i class="fas fa-chevron-right"></i>
                    </div>
                @endif
            @endforeach
        </div>
    </div>
</div>

<div class="row g-4 mb-4">
    {{-- Left Column --}}
    <div class="col-lg-8">
        {{-- Today's Action Panel --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-3 d-flex justify-content-between align-items-center">
                <h3 class="fs-6 fw-bold m-0"><i class="fas fa-bolt text-warning me-2"></i> Today's Action Panel</h3>
                <span class="badge bg-light text-secondary border">Most Important</span>
            </div>
            <div class="card-body p-3">
                <h4 class="text-uppercase text-secondary fw-bold mb-3 ms-1" style="font-size: 0.7rem;">📞 Follow-ups Today</h4>
                @forelse($followUpsToday as $fled)
                    <div class="d-flex align-items-center gap-3 p-2 border-bottom">
                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle d-flex align-items-center justify-content-center" style="width:36px; height:36px;"><i class="fas fa-phone"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-bold" style="font-size: 0.85rem;">{{ $fled->name }}</div>
                            <div class="text-secondary small">{{ $fled->next_follow_up?->format('h:i A') }} • {{ Str::limit($fled->reminder_note, 50) }}</div>
                        </div>
                        <a href="{{ route('admin.leads.show', $fled->id) }}" class="btn btn-sm btn-light border fw-bold text-primary">Call</a>
                    </div>
                @empty
                    <div class="text-center text-secondary small p-4">No follow-ups for today</div>
                @endforelse

                <h4 class="text-uppercase text-secondary fw-bold mt-4 mb-3 ms-1" style="font-size: 0.7rem;">🏠 Site Visits</h4>
                @forelse($visitsToday as $visit)
                    <div class="d-flex align-items-center gap-3 p-2 border-bottom">
                        <div class="rounded-circle d-flex align-items-center justify-content-center" style="width:36px; height:36px; background:rgba(163,113,247,0.1); color:#a371f7;"><i class="fas fa-home"></i></div>
                        <div class="flex-grow-1">
                            <div class="fw-bold" style="font-size: 0.85rem;">{{ $visit->lead?->name }}</div>
                            <div class="text-secondary small">{{ \Carbon\Carbon::parse($visit->time)->format('h:i A') }} • Site Inspection</div>
                        </div>
                        <span class="badge badge-appointment fw-bold">Scheduled</span>
                    </div>
                @empty
                    <div class="text-center text-secondary small p-4 border-bottom">No site visits today</div>
                @endforelse
            </div>
        </div>

        {{-- Revenue & performance Graph --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-3">
                <h3 class="fs-6 fw-bold m-0 border-0 p-0">Revenue & Performance</h3>
            </div>
            <div class="card-body">
                <canvas id="revenueChart" height="150"></canvas>
            </div>
        </div>
    </div>

    {{-- Right Column --}}
    <div class="col-lg-4">
        {{-- Alerts Section --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-3">
                <h3 class="fs-6 fw-bold m-0 border-0 p-0">Proactive Alerts</h3>
            </div>
            <div class="card-body p-3">
                @if($alerts['not_contacted'] > 0)
                    <div class="alert alert-danger d-flex justify-content-between align-items-center p-2 mb-2">
                        <span class="small fw-semibold">🔴 Leads not contacted</span>
                        <span class="badge bg-danger rounded-pill">{{ $alerts['not_contacted'] }}</span>
                    </div>
                @endif
                @if($alerts['quotes_pending'] > 0)
                    <div class="alert alert-warning d-flex justify-content-between align-items-center p-2 mb-2">
                        <span class="small fw-semibold">🟡 Quotes pending</span>
                        <span class="badge bg-warning text-dark rounded-pill">{{ $alerts['quotes_pending'] }}</span>
                    </div>
                @endif
                @if($alerts['idle_leads'] > 0)
                    <div class="alert alert-info d-flex justify-content-between align-items-center p-2 mb-2">
                        <span class="small fw-semibold">🔵 Idle leads (>3 days)</span>
                        <span class="badge bg-info text-dark rounded-pill">{{ $alerts['idle_leads'] }}</span>
                    </div>
                @endif
                
                @php $totalAlerts = ($alerts['not_contacted'] ?? 0) + ($alerts['quotes_pending'] ?? 0) + ($alerts['idle_leads'] ?? 0); @endphp
                @if($totalAlerts == 0)
                    <div class="text-center p-4 text-success">
                        <i class="fas fa-check-circle fs-2 mb-2"></i>
                        <div class="small fw-bold">Everything is on track!</div>
                    </div>
                @endif
            </div>
        </div>

        {{-- Installation Tracker --}}
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white border-bottom p-3">
                <h3 class="fs-6 fw-bold m-0 border-0 p-0">Installation Tracker</h3>
            </div>
            <div class="card-body p-3">
                <div class="row g-2 text-center">
                    <div class="col-4">
                        <div class="bg-light p-2 rounded">
                            <div class="fs-5 fw-bold text-warning">{{ $installStats['pending'] }}</div>
                            <div class="text-uppercase text-secondary fw-bold" style="font-size:0.6rem;">Pending</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-2 rounded">
                            <div class="fs-5 fw-bold text-primary">{{ $installStats['progress'] }}</div>
                            <div class="text-uppercase text-secondary fw-bold" style="font-size:0.6rem;">In Progress</div>
                        </div>
                    </div>
                    <div class="col-4">
                        <div class="bg-light p-2 rounded">
                            <div class="fs-5 fw-bold text-success">{{ $installStats['completed'] }}</div>
                            <div class="text-uppercase text-secondary fw-bold" style="font-size:0.6rem;">Today</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener("DOMContentLoaded", function() {
        if(document.getElementById('revenueChart')){
            new Chart(document.getElementById('revenueChart'), {
                type: 'line',
                data: {
                    labels: {!! json_encode(collect($revenueTrend ?? [])->pluck('label')) !!},
                    datasets: [{
                        label: 'Monthly Revenue',
                        data: {!! json_encode(collect($revenueTrend ?? [])->pluck('value')) !!},
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.4,
                        fill: true
                    }]
                },
                options: {
                    responsive: true,
                    plugins: { legend: { display: false } },
                    scales: {
                        y: { grid: { color: '#e9ecef' }, ticks: { color: '#6c757d' } },
                        x: { grid: { display: false }, ticks: { color: '#6c757d' } }
                    }
                }
            });
        }
    });
</script>

@endsection
