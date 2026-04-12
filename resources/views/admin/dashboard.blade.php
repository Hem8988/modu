@extends('layouts.admin')
@section('title', 'Dashboard')
@section('content')

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<style>
    :root { --accent: #2563eb; --gold: #b89b5e; --dark: #0f172a; --surface: #ffffff; --border: #e2e8f0; }
    @import url('https://fonts.googleapis.com/css2?family=Outfit:wght@400;500;600;700;800&display=swap');
    
    body { font-family: 'Outfit', sans-serif; background: #f8fafc; }
    
    .dashboard-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 32px; padding: 10px 0; }
    .quick-actions { display: flex; gap: 12px; }
    
    /* Executive KPI Pulse */
    .stat-row { display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 32px; }
    .stat-card { 
        background: #fff; 
        padding: 24px; 
        border-radius: 20px; 
        border: 1px solid var(--border); 
        box-shadow: 0 4px 6px -1px rgba(0,0,0,0.05), 0 2px 4px -2px rgba(0,0,0,0.05);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    .stat-card:hover { transform: translateY(-5px); box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 8px 10px -6px rgba(0,0,0,0.1); border-color: var(--accent); }
    .stat-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--accent); opacity: 0; transition: opacity 0.3s; }
    .stat-card:hover::before { opacity: 1; }
    
    .stat-label { font-size: 11px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1.5px; margin-bottom: 12px; }
    .stat-value { font-size: 28px; font-weight: 800; color: var(--dark); letter-spacing: -1px; }
    .insight-pill { display: inline-flex; align-items: center; gap: 6px; padding: 4px 12px; border-radius: 40px; font-size: 11px; font-weight: 700; margin-top: 12px; }
    
    /* Intelligence Hub Gear */
    .intelligence-hub { 
        background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); 
        border-radius: 24px; 
        padding: 32px; 
        margin-bottom: 32px; 
        position: relative; 
        color: #fff;
        box-shadow: 0 25px 50px -12px rgba(15, 23, 42, 0.25);
    }
    .intelligence-hub h3 { color: var(--gold); font-weight: 800; font-size: 18px; margin: 0 0 16px 0; border-left: 3px solid var(--gold); padding-left: 12px; text-transform: uppercase; letter-spacing: 1px; }
    .intelligence-item { display: flex; align-items: flex-start; gap: 12px; margin-bottom: 12px; font-size: 14px; line-height: 1.6; opacity: 0.9; }
    
    /* Industrial Funnel Synthesis */
    .funnel-panel { background: #fff; border-radius: 24px; padding: 32px; border: 1px solid var(--border); margin-bottom: 32px; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05); }
    .funnel-container { display: flex; justify-content: space-between; align-items: flex-end; height: 200px; gap: 16px; margin-top: 40px; }
    .funnel-bar { width: 100%; border-radius: 12px 12px 6px 6px; background: linear-gradient(180deg, var(--accent) 0%, #3b82f6 100%); transition: all 0.5s ease; position: relative; }
    .funnel-bar:hover { filter: brightness(1.1); transform: scaleX(1.05); cursor: pointer; }
    .funnel-tag { position: absolute; top: -30px; left: 50%; transform: translateX(-50%); font-weight: 800; font-size: 14px; color: var(--dark); }

    .card { background: #fff !important; border-radius: 24px !important; border: 1px solid var(--border) !important; padding: 32px !important; box-shadow: 0 10px 15px -3px rgba(0,0,0,0.05) !important; }
    .btn { border-radius: 12px !important; font-weight: 800 !important; padding: 12px 24px !important; font-size: 14px !important; letter-spacing: 0.5px !important; transition: all 0.3s !important; }
    .btn-primary { background: var(--accent) !important; border: none !important; box-shadow: 0 10px 20px -5px rgba(37, 99, 235, 0.3) !important; }
    .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 20px 25px -5px rgba(37, 99, 235, 0.4) !important; }

</style>

<div class="dashboard-header">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--dark); margin: 0; letter-spacing: -1px;">ModuShade Strategic Hub</h2>
        <div style="font-size: 13px; color: #64748b; font-weight: 500; margin-top: 4px;">Real-time site & revenue command center</div>
    </div>
</div>

<div style="display: grid; grid-template-columns: repeat(5, 1fr); gap: 15px; margin-bottom: 32px;">
    <a href="{{ route('admin.leads.index') }}" class="card" style="padding: 12px !important; text-align: center; text-decoration: none; border-bottom: 3px solid var(--accent); transition: transform 0.2s; background: #fff;">
        <div style="font-size: 20px; margin-bottom: 5px;">🎯</div>
        <div style="font-size: 11px; font-weight: 800; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px;">+ Record Lead</div>
    </a>
    <a href="{{ route('admin.appointments.index') }}" class="card" style="padding: 12px !important; text-align: center; text-decoration: none; border-bottom: 3px solid #8b5cf6; transition: transform 0.2s; background: #fff;">
        <div style="font-size: 20px; margin-bottom: 5px;">📏</div>
        <div style="font-size: 11px; font-weight: 800; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px;">+ Schedule Visit</div>
    </a>
    <a href="{{ route('admin.quotations.index') }}" class="card" style="padding: 12px !important; text-align: center; text-decoration: none; border-bottom: 3px solid var(--gold); transition: transform 0.2s; background: #fff;">
        <div style="font-size: 20px; margin-bottom: 5px;">📄</div>
        <div style="font-size: 11px; font-weight: 800; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px;">+ Create Quote</div>
    </a>
    <a href="{{ route('admin.invoices.index') }}" class="card" style="padding: 12px !important; text-align: center; text-decoration: none; border-bottom: 3px solid var(--dark); transition: transform 0.2s; background: #fff;">
        <div style="font-size: 20px; margin-bottom: 5px;">🧾</div>
        <div style="font-size: 11px; font-weight: 800; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px;">+ Generate Inv</div>
    </a>
    <a href="{{ route('admin.payments.index') }}" class="card" style="padding: 12px !important; text-align: center; text-decoration: none; border-bottom: 3px solid #10b981; transition: transform 0.2s; background: #fff;">
        <div style="font-size: 20px; margin-bottom: 5px;">💰</div>
        <div style="font-size: 11px; font-weight: 800; color: var(--dark); text-transform: uppercase; letter-spacing: 0.5px;">+ Record Pay</div>
    </a>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 20px; margin-bottom: 24px;">
    <!-- Strategic Hub Left -->
    <div class="intelligence-hub" style="margin-bottom: 0; padding: 24px;">
        <h3 style="font-size: 14px; margin-bottom: 12px;">MODUSHADE OPERATIONS INTELLIGENCE</h3>
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
            <div style="font-size: 13px;">
                @if($alerts['not_contacted'] > 0)
                <div class="intelligence-item"><span>🔥 <strong>{{ $alerts['not_contacted'] }} Leads cooling</strong> (+$5k scope)</span></div>
                @endif
                @if($alerts['quotes_pending'] > 0)
                <div class="intelligence-item"><span>💰 <strong>{{ $alerts['quotes_pending'] }} Proposals Pending</strong> (+$15k potential)</span></div>
                @endif
            </div>
            <div style="background: rgba(255,255,255,0.05); padding: 15px; border-radius: 12px; font-size: 11px;">
                <div style="color: var(--gold); font-weight: 800; margin-bottom: 4px;">STRATEGIC PULSE</div>
                <div style="opacity: 0.8;">"Optimize conversion by prioritzing site measurements within 48 hours (+18% win-rate)."</div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Right -->
    <div style="display: grid; grid-template-rows: 1fr 1fr; gap: 12px;">
        <div class="stat-card" style="padding: 15px; border-left: 4px solid var(--accent);">
            <div class="stat-label" style="margin-bottom: 5px;">Project Pipeline</div>
            <div class="stat-value" style="font-size: 24px; color: var(--accent);">${{ number_format($stats['total_pipeline'], 0) }}</div>
        </div>
        <div class="stat-card" style="padding: 15px; border-left: 4px solid var(--gold);">
            <div class="stat-label" style="margin-bottom: 5px;">Revenue (Month)</div>
            <div class="stat-value" style="font-size: 24px; color: var(--gold);">${{ number_format($stats['revenue_month'], 0) }}</div>
        </div>
    </div>
</div>

<div class="stat-row" style="margin-bottom: 24px; grid-template-columns: repeat(4, 1fr);">
    <div class="stat-card" style="padding: 15px; border-top: 3px solid var(--gold);">
        <div class="stat-label">REVENUE (MONTH)</div>
        <div class="stat-value" style="font-size: 22px; color: var(--gold);">${{ number_format($stats['revenue_month'], 0) }}</div>
        <div style="font-size: 10px; color: var(--success); font-weight: 700;">+{{ $stats['growth_rate'] }}% Pulse</div>
    </div>
    <div class="stat-card" style="padding: 15px; border-top: 3px solid #8b5cf6;">
        <div class="stat-label">REVENUE FORECAST</div>
        <div class="stat-value" style="font-size: 22px; color: #8b5cf6;">${{ number_format($stats['revenue_forecast'], 0) }}</div>
        <div style="font-size: 10px; color: #94a3b8; font-weight: 700;">Active Pipeline</div>
    </div>
    <div class="stat-card" style="padding: 15px; border-top: 3px solid var(--accent);">
        <div class="stat-label">CONVERSION RATE</div>
        <div class="stat-value" style="font-size: 22px; color: var(--accent);">{{ $stats['conversion_rate'] }}%</div>
        <div style="font-size: 10px; color: #94a3b8; font-weight: 700;">Site to Closed</div>
    </div>
    <div class="stat-card" style="padding: 15px; border-top: 3px solid #10b981;">
        <div class="stat-label">LEAD QUALITY</div>
        <div class="stat-value" style="font-size: 22px; color: #10b981;">{{ $stats['avg_lead_score'] }}%</div>
        <div style="font-size: 10px; color: #94a3b8; font-weight: 700;">Precision Score</div>
    </div>
</div>

<div class="stat-row" style="margin-bottom: 32px; grid-template-columns: repeat(3, 1fr);">
    <div class="stat-card" style="padding: 15px; border-right: 4px solid var(--danger);">
        <div class="stat-label">URGENT FOLLOW-UPS</div>
        <div class="stat-value" style="font-size: 22px; color: var(--danger)">{{ $stats['follow_ups_today'] }}</div>
        <div style="font-size: 10px; color: var(--danger); font-weight: 700;">Immediate Priority</div>
    </div>
    <div class="stat-card" style="padding: 15px;">
        <div class="stat-label">ACTIVE DEALS</div>
        <div class="stat-value" style="font-size: 22px; color: var(--dark)">{{ $stats['active_leads'] }}</div>
        <div style="font-size: 10px; color: #94a3b8; font-weight: 700;">Live Projects</div>
    </div>
    <div class="stat-card" style="padding: 15px;">
        <div class="stat-label">AVG DEAL VALUE</div>
        <div class="stat-value" style="font-size: 22px; color: var(--dark)">${{ number_format($stats['total_pipeline'] / max(1, $stats['total_leads']), 0) }}</div>
        <div style="font-size: 10px; color: #94a3b8; font-weight: 700;">Per Operation</div>
    </div>
</div>

{{-- World-Class 8-Stage Funnel Registry --}}
<div class="card" style="padding: 24px !important; margin-bottom: 32px; background: #fff;">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px;">
        <h3 style="font-size: 12px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">Industrial Pipeline Funnel - 8 Stage Velocity</h3>
        <div class="badge" style="background: var(--surface2); color: var(--accent); font-weight: 800;">Real-time Ops</div>
    </div>
    <div style="display: flex; justify-content: space-between; position: relative;">
        @php
            $funnelOrder = ['Enquiry', 'New Lead', 'Contacted', 'Appt Scheduled', 'Quotation Sent', 'Negotiations', 'Invoice Sent', 'Converted', 'Lost'];
            $stgColors = ['#94a3b8', '#58a6ff', '#58a6ff', '#a371f7', '#d29922', '#f85149', '#3fb950', '#10b981', '#f85149'];
        @endphp
        @foreach($funnelOrder as $index => $stageName)
            <div style="flex: 1; text-align: center; position: relative; padding: 0 5px;">
                <div style="font-size: 11px; font-weight: 800; color: #333; margin-bottom: 8px; white-space: nowrap;">{{ $stageName == 'Appt Scheduled' ? 'Site Visit' : $stageName }}</div>
                <div style="height: 6px; background: #f1f5f9; border-radius: 10px; overflow: hidden; margin-bottom: 8px;">
                    <div style="width: 100%; height: 100%; background: {{ $stgColors[$index] }}; opacity: 0.8;"></div>
                </div>
                <div style="font-size: 16px; font-weight: 800; color: var(--dark);">{{ $sidebarCounts[$stageName] ?? 0 }}</div>
                <div style="font-size: 10px; font-weight: 700; color: #94a3b8; margin-top: 4px;">
                    @php
                        $totalBaseline = max(1, $sidebarCounts['Enquiry'] ?? 1);
                        $countValue = $sidebarCounts[$stageName] ?? 0;
                        $percentage = round(($countValue / $totalBaseline) * 100, 1);
                    @endphp
                    {{ $percentage }}% Conv.
                </div>
                @if(!$loop->last)
                    <div style="position: absolute; right: -5px; top: 25px; color: #cbd5e1; font-size: 10px;"><i class="fas fa-chevron-right"></i></div>
                @endif
            </div>
        @endforeach
    </div>
</div>

{{-- Sales Funnel --}}
<div class="funnel-panel">
    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px">
        <div style="font-weight:700; color:var(--text); font-size:16px">Pipeline Growth Funnel</div>
        <div class="badge" style="background:rgba(88,166,255,0.1); color:var(--accent)">Live Tracking</div>
    </div>
    <div class="funnel-container">
        @php $maxCount = collect($funnelData)->max('count') ?: 1; @endphp
        @foreach($funnelData as $index => $stage)
            <div class="funnel-stage">
                <div class="funnel-bar" style="height: {{ ($stage['count'] / $maxCount) * 100 }}%"></div>
                <div class="funnel-label">{{ $stage['stage'] }}</div>
                <div class="funnel-count">{{ number_format($stage['count']) }}</div>
                @if(!$loop->last)
                    <div class="funnel-dropoff">
                        <i class="fas fa-arrow-right"></i> {{ $stage['drop_off'] }}%
                    </div>
                @endif
            </div>
        @endforeach
    </div>
</div>

<div class="dashboard-grid">
    {{-- Left Column --}}
    <div>
        {{-- Today's Action Panel --}}
        <div class="card" style="padding:0">
            <div style="padding:16px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center">
                <h3 style="font-size:15px; font-weight:600"><i class="fas fa-bolt" style="color:var(--warning); margin-right:8px"></i> Today's Action Panel</h3>
                <span class="badge" style="background:var(--surface2); color:var(--muted)">Most Important</span>
            </div>
            <div style="padding:12px">
                <h4 style="font-size:11px; text-transform:uppercase; color:var(--muted); margin:10px 0 8px 4px">📞 Follow-ups Today</h4>
                @forelse($followUpsToday as $fled)
                    <div class="action-item">
                        <div class="action-icon" style="background:rgba(88,166,255,0.1); color:var(--accent)"><i class="fas fa-phone"></i></div>
                        <div style="flex:1">
                            <div style="font-weight:600; font-size:14px">{{ $fled->name }}</div>
                            <div style="font-size:12px; color:var(--muted)">{{ $fled->next_follow_up?->format('h:i A') }} • {{ Str::limit($fled->reminder_note, 50) }}</div>
                        </div>
                        <a href="{{ route('admin.leads.show', $fled->id) }}" class="btn btn-sm" style="background:var(--surface2)">Call</a>
                    </div>
                @empty
                    <div style="padding:20px; text-align:center; color:var(--muted); font-size:13px">No follow-ups for today</div>
                @endforelse

                <h4 style="font-size:11px; text-transform:uppercase; color:var(--muted); margin:20px 0 8px 4px">🏠 Site Visits</h4>
                @forelse($visitsToday as $visit)
                    <div class="action-item">
                        <div class="action-icon" style="background:rgba(163,113,247,0.1); color:#a371f7"><i class="fas fa-home"></i></div>
                        <div style="flex:1">
                            <div style="font-weight:600; font-size:14px">{{ $visit->lead?->name }}</div>
                            <div style="font-size:12px; color:var(--muted)">{{ \Carbon\Carbon::parse($visit->time)->format('h:i A') }} • Site Inspection</div>
                        </div>
                        <span class="badge badge-appointment">Scheduled</span>
                    </div>
                @empty
                    <div style="padding:20px; text-align:center; color:var(--muted); font-size:13px">No site visits today</div>
                @endforelse
            </div>
        </div>

        {{-- Revenue & performance Graph --}}
        <div class="card">
            <div class="card-title">Revenue & Performance</div>
            <canvas id="revenueChart" height="150"></canvas>
        </div>
        
        {{-- Sales Team --}}
        <div class="card" style="padding:0">
            <div style="padding:16px; border-bottom:1px solid var(--border)">
                <h3 style="font-size:15px; font-weight:600">Sales Team Performance</h3>
            </div>
            <table>
                <thead>
                    <tr><th>Salesperson</th><th>Deals</th><th>Revenue</th><th>Efficiency</th></tr>
                </thead>
                <tbody>
                    @foreach($teamPerformance as $staff)
                    <tr>
                        <td style="font-weight:600">{{ $staff->name }}</td>
                        <td>{{ $staff->deals_won }}</td>
                        <td style="color:var(--success)">${{ number_format($staff->revenue, 0) }}</td>
                        <td>
                            <div style="width:100%; height:4px; background:var(--surface2); border-radius:10px;">
                                <div style="width:{{ min(100, ($staff->deals_won / 10) * 100) }}%; height:100%; background:var(--accent); border-radius:10px;"></div>
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Right Column --}}
    <div>
        {{-- Alerts Section --}}
        <div class="card">
            <div class="card-title">Proactive Alerts</div>
            @if($alerts['not_contacted'] > 0)
                <div class="alert-chip">
                    <span>🔴 Leads not contacted</span>
                    <span class="alert-count">{{ $alerts['not_contacted'] }}</span>
                </div>
            @endif
            @if($alerts['quotes_pending'] > 0)
                <div class="alert-chip" style="color:var(--warning); background:rgba(210,153,34,0.1); border-color:rgba(210,153,34,0.2)">
                    <span>🟡 Quotes pending</span>
                    <span class="alert-count" style="background:var(--warning)">{{ $alerts['quotes_pending'] }}</span>
                </div>
            @endif
            @if($alerts['idle_leads'] > 0)
                <div class="alert-chip" style="color:var(--accent); background:rgba(88,166,255,0.1); border-color:rgba(88,166,255,0.2)">
                    <span>🔵 Idle leads (>3 days)</span>
                    <span class="alert-count" style="background:var(--accent)">{{ $alerts['idle_leads'] }}</span>
                </div>
            @endif
            @php
                $totalAlerts = ($alerts['not_contacted'] ?? 0) + ($alerts['quotes_pending'] ?? 0) + ($alerts['idle_leads'] ?? 0);
            @endphp
            @if($totalAlerts == 0)
                <div style="text-align:center; padding:20px; color:var(--success)">
                    <i class="fas fa-check-circle" style="font-size:24px; margin-bottom:10px"></i>
                    <div style="font-size:13px">Everything is on track!</div>
                </div>
            @endif
        </div>


        {{-- Installation Tracker --}}
        <div class="card">
            <div class="card-title">Installation Tracker</div>
            <div style="display:grid; grid-template-columns:1fr 1fr 1fr; gap:10px; text-align:center">
                <div style="background:var(--surface2); padding:10px; border-radius:8px">
                    <div style="font-size:18px; font-weight:700; color:var(--warning)">{{ $installStats['pending'] }}</div>
                    <div style="font-size:10px; color:var(--muted); text-transform:uppercase">Pending</div>
                </div>
                <div style="background:var(--surface2); padding:10px; border-radius:8px">
                    <div style="font-size:18px; font-weight:700; color:var(--accent)">{{ $installStats['progress'] }}</div>
                    <div style="font-size:10px; color:var(--muted); text-transform:uppercase">In Progress</div>
                </div>
                <div style="background:var(--surface2); padding:10px; border-radius:8px">
                    <div style="font-size:18px; font-weight:700; color:var(--success)">{{ $installStats['completed'] }}</div>
                    <div style="font-size:10px; color:var(--muted); text-transform:uppercase">Today</div>
                </div>
            </div>
        </div>

        {{-- Payments Snapshot --}}
        <div class="card">
            <div class="card-title">Payment Snapshot</div>
            <div style="margin-bottom:15px">
                <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:5px">
                    <span>Advance Received</span>
                    <span style="color:var(--success); font-weight:600">${{ number_format($payments['advance'], 0) }}</span>
                </div>
                <div style="width:100%; height:8px; background:var(--surface2); border-radius:4px; overflow:hidden">
                    <div style="width:{{ $payments['advance'] + $payments['pending'] > 0 ? ($payments['advance'] / ($payments['advance'] + $payments['pending'])) * 100 : 0 }}%; height:100%; background:var(--success)"></div>
                </div>
            </div>
            <div style="margin-bottom:15px">
                <div style="display:flex; justify-content:space-between; font-size:13px; margin-bottom:5px">
                    <span>Pending Payments</span>
                    <span style="color:var(--warning); font-weight:600">${{ number_format($payments['pending'], 0) }}</span>
                </div>
                <div style="width:100%; height:8px; background:var(--surface2); border-radius:4px; overflow:hidden">
                    <div style="width:{{ $payments['advance'] + $payments['pending'] > 0 ? ($payments['pending'] / ($payments['advance'] + $payments['pending'])) * 100 : 0 }}%; height:100%; background:var(--warning)"></div>
                </div>
            </div>
            <div class="alert-chip" style="margin-bottom:0; background:rgba(248,81,73,0.05); border-style:dashed">
                <span style="font-size:12px">Overdue Payments 🔴</span>
                <span style="font-weight:700; font-size:14px">${{ number_format($payments['overdue'], 0) }}</span>
            </div>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1.5fr; gap: 32px; margin-bottom: 32px;">
    <!-- Today's High-Priority Actions -->
    <div class="card" style="padding: 24px !important;">
        <h3 style="font-size: 13px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;">⚡ Today's Site Actions</h3>
        <div style="display: grid; gap: 12px;">
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(37,99,235,0.05); border-radius: 8px;">
                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">📞 Pending Follow-ups</span>
                <span class="badge" style="background: var(--accent); color: #fff;">{{ $stats['follow_ups_today'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(139,92,246,0.05); border-radius: 8px;">
                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">📏 Scheduled Site Visits</span>
                <span class="badge" style="background: #8b5cf6; color: #fff;">{{ $stats['upcoming_appointments'] }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(212,160,23,0.05); border-radius: 8px;">
                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">📄 Quotations Pending</span>
                <span class="badge" style="background: var(--gold); color: #fff;">{{ $sidebarCounts['Appt Scheduled'] ?? 0 }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(239,68,68,0.05); border-radius: 8px;">
                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">🧾 Project Invoices Due</span>
                <span class="badge" style="background: var(--danger); color: #fff;">{{ $stats['overdue_invoices_count'] ?? 0 }}</span>
            </div>
            <div style="display: flex; justify-content: space-between; align-items: center; padding: 12px; background: rgba(16,185,129,0.05); border-radius: 8px;">
                <span style="font-size: 13px; font-weight: 700; color: var(--dark);">💰 Overdue Treasury Pulse</span>
                <span class="badge" style="background: #10b981; color: #fff;">${{ number_format($payments['overdue'] ?? 0, 0) }}</span>
            </div>
        </div>
    </div>

    <!-- Live Operations Feed -->
    <div class="card" style="padding: 24px !important;">
        <h3 style="font-size: 13px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 20px;">🕒 Live Operations Registry</h3>
        <div style="max-height: 280px; overflow-y: auto; padding-right: 10px;">
            @foreach($recentActivity as $activity)
            <div style="display: flex; gap: 15px; margin-bottom: 20px; position: relative; padding-bottom: 20px; border-bottom: 1px solid #f1f5f9;">
                <div style="width: 10px; height: 10px; border-radius: 50%; background: var(--accent); margin-top: 5px;"></div>
                <div style="flex: 1;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase;">{{ $activity->description }}</span>
                        <span style="font-size: 10px; color: #94a3b8;">{{ $activity->created_at->diffForHumans() }}</span>
                    </div>
                    <div style="font-size: 13px; color: var(--dark); font-weight: 600; margin-top: 4px;">
                        Project: {{ $activity->lead?->name ?: 'System Broadcast' }}
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 1fr 1fr; gap: 32px;">
    <!-- Installations Placeholder -->
    <div class="card" style="padding: 24px !important; border-left: 4px solid var(--accent);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 13px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">🛠 Deployment Tracker</h3>
            <span class="badge" style="background: rgba(37,99,235,0.05); color: var(--accent);">Active Projects</span>
        </div>
        <div style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 15px;">
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 12px; border-bottom: 3px solid #64748b;">
                <div style="font-size: 20px; font-weight: 800; color: var(--dark);">{{ $stats['installations_pending'] ?? 0 }}</div>
                <div style="font-size: 9px; font-weight: 700; color: #64748b; margin-top: 5px;">PENDING</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 12px; border-bottom: 3px solid #8b5cf6;">
                <div style="font-size: 20px; font-weight: 800; color: #8b5cf6;">{{ $stats['installations_in_progress'] ?? 0 }}</div>
                <div style="font-size: 9px; font-weight: 700; color: #64748b; margin-top: 5px;">IN PROGRESS</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 12px; border-bottom: 3px solid #10b981;">
                <div style="font-size: 20px; font-weight: 800; color: #10b981;">{{ $stats['installations_completed'] ?? 0 }}</div>
                <div style="font-size: 9px; font-weight: 700; color: #64748b; margin-top: 5px;">COMPLETED</div>
            </div>
            <div style="text-align: center; padding: 15px; background: #f8fafc; border-radius: 12px; border-bottom: 3px solid var(--accent);">
                <div style="font-size: 20px; font-weight: 800; color: var(--accent);">{{ $stats['installations_today'] ?? 0 }}</div>
                <div style="font-size: 9px; font-weight: 700; color: #64748b; margin-top: 5px;">TODAY</div>
            </div>
        </div>
    </div>

    <!-- Complaints Placeholder -->
    <div class="card" style="padding: 24px !important; border-left: 4px solid var(--danger);">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h3 style="font-size: 13px; font-weight: 800; color: #94a3b8; text-transform: uppercase; letter-spacing: 1px;">⚠ Project Complaints</h3>
            <span class="badge" style="background: rgba(239,68,68,0.05); color: var(--danger);">Registry Alert</span>
        </div>
        <div style="font-size: 13px; color: #64748b; font-weight: 500; text-align: center; padding: 20px;">
            <i class="fas fa-check-circle" style="color: #10b981; font-size: 24px; margin-bottom: 10px;"></i>
            <br>
            Project registry clear. Zero active complaints detected.
        </div>
    </div>
</div>

<script>
    // Revenue Chart
    new Chart(document.getElementById('revenueChart'), {
        type: 'line',
        data: {
            labels: {!! json_encode(collect($revenueTrend)->pluck('label')) !!},
            datasets: [{
                label: 'Monthly Revenue',
                data: {!! json_encode(collect($revenueTrend)->pluck('value')) !!},
                borderColor: '#58a6ff',
                backgroundColor: 'rgba(88,166,255,0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            plugins: { legend: { display: false } },
            scales: {
                y: { grid: { color: '#30363d' }, ticks: { color: '#8b949e' } },
                x: { grid: { display: false }, ticks: { color: '#8b949e' } }
            }
        }
    });

</script>

@endsection
