@extends('layouts.admin')
@section('title','Lead Details — '.$lead->name)
@section('content')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

{{-- Professional Header with Stats and Actions --}}
<div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-start; margin-bottom:24px; gap:20px;">
    <div style="flex:1">
        <h2 style="font-size:22px; font-weight:700; margin-bottom:4px; display:flex; align-items:center; gap:10px;">
            {{ $lead->name }} 
            <span class="badge badge-{{ $lead->status }}" style="font-size:11px; padding:4px 10px;">{{ str_replace('_',' ',$lead->status) }}</span>
        </h2>
        <div style="font-size:14px; color:var(--muted); display:flex; gap:16px; font-weight:500;">
            <span><span style="color:var(--accent)">📞</span> {{ $lead->phone }}</span>
            <span><span style="color:var(--gold)">✉</span> {{ $lead->email ?: 'No email' }}</span>
            <span><span style="color:var(--success)">📍</span> {{ $lead->city ?: 'No city' }}</span>
        </div>
    </div>
    <div style="display:flex; gap:8px;">
        <a href="tel:{{ $lead->phone }}" class="btn btn-sm" style="background:rgba(16,185,129,0.08); color:var(--success); border:1px solid rgba(16,185,129,0.1); padding:8px 12px; font-weight:700;">☎ Call</a>
        <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$lead->phone) }}" target="_blank" class="btn btn-sm" style="background:rgba(37,99,235,0.08); color:var(--accent); border:1px solid rgba(37,99,235,0.1); padding:8px 12px; font-weight:700;">💬 WhatsApp</a>
        
        @php
            $fullPipeline = [
                'new_lead'             => ['label' => 'New Lead', 'icon' => '🎯'],
                'contacted'            => ['label' => 'Contacted', 'icon' => '📻'],
                'site_visit_scheduled' => ['label' => 'Appointment', 'icon' => '📅'],
                'quotation_sent'       => ['label' => 'Quotation Sent', 'icon' => '📄'],
                'invoice_sent'         => ['label' => 'Invoice Sent', 'icon' => '🧾'],
                'negotiation'          => ['label' => 'Negotiation', 'icon' => '🤝'],
                'deal_won'             => ['label' => 'Deal Won', 'icon' => '🏆'],
                'lost'                 => ['label' => 'Lost / Closed', 'icon' => '❌']
            ];
            $stagesKeys = array_keys($fullPipeline);
            $currentStatusIdx = array_search($lead->status, $stagesKeys);
        @endphp
        <form method="POST" action="{{ route('admin.leads.update', $lead->id) }}" id="status-quick-form" style="display:flex;">
            @csrf @method('PUT')
            <select name="status" class="form-control" style="width:220px; height:42px; border:2.5px solid var(--accent); font-weight:800; color:var(--accent); background:rgba(37,99,235,0.05); border-radius:10px; padding:0 12px; cursor:pointer;" onchange="this.form.submit()">
                @foreach($fullPipeline as $val => $data)
                    @php 
                        $thisStepIdx = array_search($val, $stagesKeys);
                        // Disable if it's a previous step (lower index), but allow 'lost' (unless already won) 
                        // and allow current status.
                        $isDisabled = ($thisStepIdx < $currentStatusIdx && $val !== 'lost') || ($lead->status === 'deal_won' && $val !== 'deal_won');
                    @endphp
                    <option value="{{ $val }}" 
                        {{ $lead->status === $val ? 'selected' : '' }}
                        {{ $isDisabled ? 'disabled' : '' }}
                        style="{{ $isDisabled ? 'opacity:0.5; color:#ccc;' : '' }}">
                        {{ $data['icon'] }} {{ $data['label'] }}
                    </option>
                @endforeach
            </select>
        </form>
    </div>
</div>

@php
    $visualPipeline = [
        'new_lead'             => 'New',
        'contacted'            => 'Contacted',
        'site_visit_scheduled' => 'Appt',
        'quotation_sent'       => 'Quote',
        'invoice_sent'         => 'Invoice',
        'negotiation'          => 'Negot.',
        'deal_won'             => 'Won'
    ];
    $visualStagesArr = array_keys($visualPipeline);
    $currentVisualIdx = array_search($lead->status, $visualStagesArr);
@endphp
<div class="card" style="padding:16px 20px; margin-bottom:24px; display:flex; align-items:center; justify-content:space-between; gap:6px; overflow-x:auto;">
    @foreach($visualPipeline as $key => $label)
        @php
            $thisVisualStepIdx = array_search($key, $visualStagesArr);
            $isActive = $lead->status === $key;
            $isDone = ($currentVisualIdx !== false && $thisVisualStepIdx < $currentVisualIdx) || ($lead->status === 'deal_won');
        @endphp
        <div style="flex:1; display:flex; flex-direction:column; align-items:center; gap:6px; opacity:{{ ($isActive || $isDone) ? '1' : '0.4' }}">
            <div style="width:24px; height:24px; border-radius:50%; background:{{ $isDone ? 'var(--success)' : ($isActive ? 'var(--accent)' : 'var(--surface2)') }}; color:{{ ($isActive || $isDone) ? '#fff' : 'var(--muted)' }}; display:flex; align-items:center; justify-content:center; font-size:11px; font-weight:800; border:2px solid {{ $isActive ? 'var(--accent)' : 'transparent' }}">
                @if($isDone) ✓ @else {{ $loop->iteration }} @endif
            </div>
            <span style="font-size:10px; text-transform:uppercase; letter-spacing:0.3px; font-weight:{{ $isActive ? '800' : '600' }}; color:{{ $isActive ? 'var(--accent)' : 'var(--text)' }}">{{ $label }}</span>
        </div>
        @if(!$loop->last)
            <div style="height:2px; flex:0.3; background:{{ $isDone ? 'var(--success)' : 'var(--surface2)' }}; margin-top:-14px;"></div>
        @endif
    @endforeach
</div>

<div class="grid-2 lead-main-grid">
<style>
    @media (min-width: 901px) {
        .lead-main-grid { grid-template-columns: 2fr 1fr; gap: 20px; }
    }
</style>
    <div>
        {{-- Professional Tabs Header --}}
        <div style="display:flex; flex-wrap:wrap; gap:10px; border-bottom:1px solid var(--border); margin-bottom:20px;">
            <button onclick="switchTab('basic')" id="tab-btn-basic" class="tab-btn active-tab">👤 Info</button>
            <button onclick="switchTab('follow')" id="tab-btn-follow" class="tab-btn">🚀 Follow-ups</button>
            <button onclick="switchTab('appt')" id="tab-btn-appt" class="tab-btn">📅 Appointments</button>
            <button onclick="switchTab('install')" id="tab-btn-install" class="tab-btn">⚒ Installations</button>
            <button onclick="switchTab('finance')" id="tab-btn-finance" class="tab-btn">💰 Finance</button>
            <button onclick="switchTab('history')" id="tab-btn-history" class="tab-btn">📜 Logs</button>
        </div>

        {{-- Tab 1: Basic Info --}}
        <div id="tab-basic" class="tab-content active-tab-content">
            <form method="POST" action="{{ route('admin.leads.update',$lead->id) }}">
                @csrf @method('PUT')
                <div class="card" style="padding:24px;">
                    <div class="grid-2">
                        <div><label class="form-label">Name</label><input class="form-control" name="name" value="{{ $lead->name }}"></div>
                        <div><label class="form-label">Phone</label><input class="form-control" name="phone" value="{{ $lead->phone }}"></div>
                        <div><label class="form-label">Email</label><input class="form-control" name="email" value="{{ $lead->email }}"></div>
                        <div><label class="form-label">City</label><input class="form-control" name="city" value="{{ $lead->city }}"></div>
                        <div style="grid-column: span 2;"><label class="form-label">Source / Campaign</label>
                            <div style="display:flex; gap:10px;">
                                <input class="form-control" name="source" value="{{ $lead->source }}" readonly style="background:var(--surface2); width:40%;">
                                <input class="form-control" name="campaign" value="{{ $lead->campaign }}" placeholder="Campaign Name">
                            </div>
                        </div>
                        <div style="grid-column: span 2;"><label class="form-label">Project / Shades Needed</label><input class="form-control" name="shades_needed" value="{{ $lead->shades_needed }}"></div>
                        <div style="grid-column: span 2;"><label class="form-label">Notes / Feedback</label><textarea class="form-control" name="feedback" rows="4">{{ $lead->feedback }}</textarea></div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="margin-top:20px; padding:12px 32px; font-weight:600;">💾 Update Profile</button>
                </div>
            </form>
        </div>

        {{-- Tab: Follow-ups --}}
        <div id="tab-follow" class="tab-content" style="display:none">
            <div class="card" style="padding:24px;">
                <h3 style="font-size:16px; font-weight:800; margin-bottom:20px; color:var(--accent); display:flex; align-items:center; gap:10px;">
                    🚀 Outreach Action Center
                </h3>
                
                {{-- Sleek Action Bar --}}
                <form method="POST" action="{{ route('admin.leads.action', $lead->id) }}" class="grid-4" style="gap:12px; margin-bottom:32px; background:var(--surface2); padding:20px; border-radius:15px; border:1px solid var(--border); box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                    @csrf <input type="hidden" name="action_type" value="follow_up">
                    <div><label class="form-label">When?</label><input class="form-control date-picker" type="text" name="date" required placeholder="Select date & time..."></div>
                    <div><label class="form-label">Channel</label>
                        <select class="form-control" name="type"><option value="call">☎ Phone Call</option><option value="whatsapp">💬 WhatsApp</option><option value="email">✉ Email Outreach</option></select>
                    </div>
                    <div><label class="form-label">What happened?</label><input class="form-control" name="notes" placeholder="e.g. Left voicemail, customer asked for price..."></div>
                    <div style="display:flex; align-items:flex-end;"><button type="submit" class="btn btn-primary" style="height:42px; padding:0 24px;">Log Activity</button></div>
                </form>

                <div style="padding-left:12px;">
                    <div style="font-size:12px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1.5px; margin-bottom:24px; display:flex; align-items:center; gap:10px;">
                        <span style="width:8px; height:8px; background:var(--accent); border-radius:50%;"></span> Activity Timeline
                    </div>
                    
                    <div class="timeline-container">
                        @forelse($lead->followUps()->orderBy('date','desc')->get() as $f)
                        <div class="timeline-item">
                            <div class="timeline-icon {{ $f->type }}">
                                @if($f->type == 'call') 📞 @elseif($f->type == 'whatsapp') 💬 @else ✉ @endif
                            </div>
                            <div class="timeline-content">
                                <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-start; margin-bottom:4px; gap:8px;">
                                    <div>
                                        <span style="font-weight:800; color:var(--text); font-size:14px;">{{ ucfirst($f->type) }} Outreach</span>
                                        <span style="color:var(--muted); font-size:12px; margin-left:8px;">{{ date('M d, h:i A', strtotime($f->date)) }}</span>
                                    </div>
                                    <div style="display:flex; align-items:center; gap:8px;">
                                        @if($f->notes)
                                        <button type="button" class="btn-icon" onclick="openMsgPopup('{{ $f->title ?? 'Outreach Details' }}', '{{ addslashes($f->notes) }}')">🔍</button>
                                        @endif
                                        <form method="POST" action="{{ route('admin.leads.action-update', ['leadId' => $lead->id, 'type' => 'follow', 'id' => $f->id]) }}">
                                            @csrf @method('PUT')
                                            <select name="status" onchange="this.form.submit()" class="status-pill {{ $f->status }}">
                                                <option value="pending" {{ $f->status === 'pending' ? 'selected' : '' }}>⏳ Pending</option>
                                                <option value="completed" {{ $f->status === 'completed' ? 'selected' : '' }}>✓ Completed</option>
                                                <option value="no_answer" {{ $f->status === 'no_answer' ? 'selected' : '' }}>📵 No Answer</option>
                                                <option value="cancelled" {{ $f->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                            </select>
                                        </form>
                                    </div>
                                </div>
                                <p class="truncate-text" style="color:var(--muted); font-size:14px; margin:0; line-height:1.4; max-width:90%;">{{ $f->notes ?: 'No specific notes recorded.' }}</p>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; padding:40px; color:var(--muted); opacity:0.6;">
                            <div style="font-size:40px; margin-bottom:10px;">🍃</div>
                            <p>No activity logs yet. Start by recording your first outreach!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Appointments --}}
        <div id="tab-appt" class="tab-content" style="display:none">
            <div class="card" style="padding:24px;">
                <h3 style="font-size:16px; font-weight:800; margin-bottom:20px; color:var(--accent); display:flex; align-items:center; gap:10px;">
                    📅 Appointment Hub
                </h3>
                
                {{-- Sleek Action Bar --}}
                <form method="POST" action="{{ route('admin.leads.action', $lead->id) }}" class="grid-4" style="gap:12px; margin-bottom:32px; background:var(--surface2); padding:20px; border-radius:15px; border:1px solid var(--border); box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                    @csrf <input type="hidden" name="action_type" value="appointment">
                    <div><label class="form-label">When?</label><input class="form-control date-picker" type="text" name="date" required placeholder="Select date & time..."></div>
                    <div><label class="form-label">Purpose</label>
                        <select class="form-control" name="type"><option value="measurement">📏 Measurement</option><option value="consultation">🤝 Consultation</option><option value="repair">⚒ Repair</option></select>
                    </div>
                    <div><label class="form-label">Requirements</label><input class="form-control" name="notes" placeholder="e.g. Bring fabric samples..."></div>
                    <div style="display:flex; align-items:flex-end;"><button type="submit" class="btn btn-primary" style="height:42px; padding:0 24px;" onclick="this.form.action += '#tab-appt'">Schedule Visit</button></div>
                </form>

                <div style="padding-left:12px;">
                    <div style="font-size:12px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1.5px; margin-bottom:24px; display:flex; align-items:center; gap:10px;">
                        <span style="width:8px; height:8px; background:var(--gold); border-radius:50%;"></span> Appointment Timeline
                    </div>
                    
                    <div class="timeline-container">
                        @forelse($lead->appointments()->orderBy('date','desc')->get() as $a)
                        <div class="timeline-item">
                            <div class="timeline-icon" style="border-color:var(--gold); color:var(--gold);">
                                @if($a->type == 'measurement') 📏 @elseif($a->type == 'consultation') 🤝 @else ⚒ @endif
                            </div>
                            <div class="timeline-content">
                                <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-start; margin-bottom:4px; gap:8px;">
                                    <div>
                                        <span style="font-weight:800; color:var(--text); font-size:14px;">{{ ucfirst($a->type) }} Visit</span>
                                        <span style="color:var(--muted); font-size:12px; margin-left:8px;">{{ date('M d, h:i A', strtotime($a->date.' '.$a->time)) }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.leads.action-update', ['leadId' => $lead->id, 'type' => 'appt', 'id' => $a->id]) }}">
                                        @csrf @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="status-pill {{ $a->status }}">
                                            <option value="pending" {{ ($a->status === 'pending' || $a->status === 'scheduled') ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="completed" {{ $a->status === 'completed' ? 'selected' : '' }}>✓ Visited</option>
                                            <option value="cancelled" {{ $a->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                        </select>
                                    </form>
                                </div>
                                <p style="color:var(--muted); font-size:14px; margin:0; line-height:1.4;">{{ $a->notes ?: 'No requirements recorded.' }}</p>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; padding:40px; color:var(--muted); opacity:0.6;">
                            <div style="font-size:40px; margin-bottom:10px;">📅</div>
                            <p>No site visits scheduled yet. Plan your first appointment!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Installations --}}
        <div id="tab-install" class="tab-content" style="display:none">
            <div class="card" style="padding:24px;">
                <h3 style="font-size:16px; font-weight:800; margin-bottom:20px; color:var(--success); display:flex; align-items:center; gap:10px;">
                    ⚒ Project Execution Hub
                </h3>
                
                {{-- Sleek Action Bar --}}
                <form method="POST" action="{{ route('admin.leads.action', $lead->id) }}" class="grid-4" style="gap:12px; margin-bottom:32px; background:var(--surface2); padding:20px; border-radius:15px; border:1px solid var(--border); box-shadow: inset 0 2px 4px rgba(0,0,0,0.02);">
                    @csrf <input type="hidden" name="action_type" value="installation">
                    <div><label class="form-label">When?</label><input class="form-control date-picker-no-time" type="text" name="date" required placeholder="Pick a date..."></div>
                    <div><label class="form-label">Assigned Team</label><input class="form-control" name="team" placeholder="Crew Name"></div>
                    <div><label class="form-label">Site Notes</label><input class="form-control" name="notes" placeholder="Specific site directions..."></div>
                    <div style="display:flex; align-items:flex-end;"><button type="submit" class="btn" style="background:var(--success); color:#fff; font-weight:700; height:42px; padding:0 24px;" onclick="this.form.action += '#tab-install'">Record Log</button></div>
                </form>
 
                <div style="padding-left:12px;">
                    <div style="font-size:12px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1.5px; margin-bottom:24px; display:flex; align-items:center; gap:10px;">
                        <span style="width:8px; height:8px; background:var(--success); border-radius:50%;"></span> Implementation History
                    </div>
                    
                    <div class="timeline-container">
                        @forelse($lead->installations()->orderBy('date','desc')->get() as $i)
                        <div class="timeline-item">
                            <div class="timeline-icon" style="border-color:var(--success); color:var(--success);">
                                ⚒
                            </div>
                            <div class="timeline-content">
                                <div style="display:flex; flex-wrap:wrap; justify-content:space-between; align-items:flex-start; margin-bottom:4px; gap:8px;">
                                    <div>
                                        <span style="font-weight:800; color:var(--text); font-size:14px;">Site Implementation</span>
                                        <span style="color:var(--success); font-weight:700; font-size:11px; margin-left:12px;">TEAM: {{ strtoupper($i->team ?: 'GENERAL') }}</span>
                                        <span style="color:var(--muted); font-size:12px; margin-left:12px;">{{ date('M d, Y', strtotime($i->date)) }}</span>
                                    </div>
                                    <form method="POST" action="{{ route('admin.leads.action-update', ['leadId' => $lead->id, 'type' => 'install', 'id' => $i->id]) }}">
                                        @csrf @method('PUT')
                                        <select name="status" onchange="this.form.submit()" class="status-pill {{ $i->status }}">
                                            <option value="pending" {{ ($i->status === 'pending' || $i->status === 'scheduled') ? 'selected' : '' }}>⏳ Pending</option>
                                            <option value="completed" {{ $i->status === 'completed' ? 'selected' : '' }}>✓ Installed</option>
                                            <option value="cancelled" {{ $i->status === 'cancelled' ? 'selected' : '' }}>❌ Cancelled</option>
                                        </select>
                                    </form>
                                </div>
                                <p style="color:var(--muted); font-size:14px; margin:0; line-height:1.4;">{{ $i->notes ?: 'No implementation notes recorded.' }}</p>
                            </div>
                        </div>
                        @empty
                        <div style="text-align:center; padding:40px; color:var(--muted); opacity:0.6;">
                            <div style="font-size:40px; margin-bottom:10px;">⚒</div>
                            <p>No project logs yet. Record your site implementation!</p>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>

        {{-- Tab: Financials --}}
        <div id="tab-finance" class="tab-content" style="display:none">
            <div class="card" style="padding:24px;">
                <h3 style="font-size:16px; font-weight:800; margin-bottom:20px; color:var(--gold); display:flex; align-items:center; gap:10px;">
                    💰 Financial Hub
                </h3>

                {{-- Action Grid --}}
                <div class="grid-2" style="margin-bottom:32px;">
                    <a href="{{ route('admin.quotations.builder', $lead->id) }}" class="btn" style="background:rgba(163,113,247,0.1); color:#7c3aed; border:1px solid rgba(163,113,247,0.2); padding:16px; border-radius:15px; font-weight:800; display:flex; align-items:center; justify-content:center; gap:12px; transition:all 0.3s;">
                        <span style="font-size:20px;">📂</span> Add New Quotation
                    </a>
                    <a href="{{ route('admin.invoices.index') }}?lead_id={{ $lead->id }}" class="btn" style="background:rgba(5,150,105,0.1); color:#059669; border:1px solid rgba(5,150,105,0.2); padding:16px; border-radius:15px; font-weight:800; display:flex; align-items:center; justify-content:center; gap:12px; transition:all 0.3s;">
                        <span style="font-size:20px;">🏦</span> Add Invoice
                    </a>
                </div>

                {{-- Integrated Proposal Registry --}}
                <div style="margin-bottom:40px;">
                    <div style="font-size:12px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1.5px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                        <span style="width:8px; height:2px; background:var(--accent);"></span> Proposal History
                    </div>
                    <div class="table-responsive" style="background:var(--surface2); border:1px solid var(--border); overflow:hidden;">
                        <table style="width:100%; border-collapse:collapse; font-size:13px;">
                            <thead>
                                <tr style="background:rgba(0,0,0,0.02); border-bottom:1px solid var(--border);">
                                    <th style="padding:12px 16px; text-align:left; font-weight:800; color:var(--muted); font-size:10px; text-transform:uppercase;">ID</th>
                                    <th style="padding:12px 16px; text-align:left; font-weight:800; color:var(--muted); font-size:10px; text-transform:uppercase;">Value</th>
                                    <th style="padding:12px 16px; text-align:center; font-weight:800; color:var(--muted); font-size:10px; text-transform:uppercase;">Status</th>
                                    <th style="padding:12px 16px; text-align:right; font-weight:800; color:var(--muted); font-size:10px; text-transform:uppercase;">Refine</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($lead->quotes()->latest()->get() as $q)
                                <tr style="border-bottom:1px solid var(--border);">
                                    <td style="padding:14px 16px; font-weight:800; color:var(--accent);">#{{ $q->quote_number }}</td>
                                    <td style="padding:14px 16px; font-weight:900; color:var(--text);">${{ number_format($q->total_amount, 2) }}</td>
                                    <td style="padding:14px 16px; text-align:center;">
                                        @php
                                            $qStatusColors = [
                                                'draft' => ['bg' => 'var(--surface2)', 'text' => 'var(--muted)', 'icon' => '⏳'],
                                                'sent' => ['bg' => '#dbeafe', 'text' => '#1e40af', 'icon' => '✉'],
                                                'accepted' => ['bg' => '#d1fae5', 'text' => '#065f46', 'icon' => '✓'],
                                                'rejected' => ['bg' => '#fee2e2', 'text' => '#991b1b', 'icon' => '❌'],
                                                'converted' => ['bg' => '#ede9fe', 'text' => '#5b21b6', 'icon' => '🎯'],
                                            ];
                                            $qs = $qStatusColors[$q->status] ?? $qStatusColors['draft'];
                                        @endphp
                                        <span style="background:{{ $qs['bg'] }}; color:{{ $qs['text'] }}; padding:4px 10px; border-radius:6px; font-size:10px; font-weight:900; text-transform:uppercase;">{{ $qs['icon'] }} {{ $q->status }}</span>
                                        @if($q->signature_data)
                                            <div style="margin-top: 4px; font-size: 9px; font-weight: 900; color: var(--success); display: flex; align-items: center; justify-content: center; gap: 3px;">
                                                <span style="font-size: 10px;">🛡️</span> SIGNED
                                            </div>
                                        @endif
                                    </td>
                                    <td style="padding:14px 16px; text-align:right;">
                                        <div style="display:flex; justify-content:flex-end; gap:8px;">
                                            @if($q->client_token)
                                            <button onclick="copyToClipboard('{{ route('quote.client', $q->client_token) }}')" class="btn-icon" title="Copy Digital Sign Link" style="background:rgba(37,99,235,0.05); color:var(--accent);">🔗</button>
                                            @endif
                                            @if($q->signature_data)
                                            <button onclick="openMsgPopup('Digital Signature Certificate', '<img src=\'{{ $q->signature_data }}\' style=\'width:300px; filter:contrast(1.2);\'><br><br>Signed by: {{ $q->signature_name }}<br>On: {{ $q->signed_at?->format('M d, Y h:i A') }}\')" class="btn-icon" title="Quick View Signature" style="background:rgba(16,185,129,0.05); color:var(--success);">🛡️</button>
                                            @endif
                                            <a href="{{ route('admin.quotations.show', $q->id) }}" class="btn-icon" title="Print/View PDF">📄</a>
                                            <a href="{{ route('admin.quotations.builder', $lead->id) }}" class="btn-icon" title="Refine Bid">🛠</a>
                                            @if($q->status !== 'converted')
                                            <a href="{{ route('admin.quotations.convert', $q->id) }}" class="btn-icon" style="background:rgba(5,150,105,0.1); color:#059669; border:1px solid rgba(5,150,105,0.2);" title="Finalize to Invoice">🎯</a>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr><td colspan="4" style="padding:32px; text-align:center; color:var(--muted); font-weight:600; font-style:italic;">No proposals tracked in history.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                <form method="POST" action="{{ route('admin.leads.update', $lead->id) }}">
                    @csrf @method('PUT')
                    <div style="font-size:12px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1.5px; margin-bottom:20px; display:flex; align-items:center; gap:10px;">
                        <span style="width:8px; height:2px; background:var(--gold);"></span> Ledger Management
                    </div>

                    <div class="grid-2" style="margin-bottom:24px;">
                        <div>
                            <label class="form-label">Budget Range</label>
                            <input class="form-control" name="budget" value="{{ $lead->budget }}" placeholder="e.g. 5k - 10k">
                        </div>
                        <div>
                            <label class="form-label">Final Amount ($)</label>
                            <input class="form-control" name="amount" type="number" step="0.01" value="{{ $lead->amount }}" placeholder="0.00">
                        </div>
                        <div>
                            <label class="form-label">Advance Paid ($)</label>
                            <input class="form-control" name="advance_amount" type="number" step="0.01" value="{{ $lead->advance_amount }}" placeholder="0.00">
                        </div>
                        <div>
                            <label class="form-label">Payment Status</label>
                            <select class="form-control" name="payment_status">
                                <option value="">— Choose Status —</option>
                                @foreach(['unpaid','partial','paid'] as $p)
                                <option value="{{ $p }}" {{ $lead->payment_status===$p?'selected':'' }}>{{ ucfirst($p) }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    <div style="text-align:right;">
                        <button type="submit" class="btn btn-primary" style="padding:12px 32px; font-weight:800; border-radius:10px;">
                            💾 Update Ledger
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- Tab: History --}}
        <div id="tab-history" class="tab-content" style="display:none">
            <div class="card" style="padding:24px;">
                <div id="activity-log" style="display:grid; gap:12px; font-size:13px;">
                    <p style="color:var(--muted);">Loading system trails...</p>
                </div>
            </div>
        </div>
    </div>

    {{-- Side Widgets (Meta Data) --}}
    <div style="display:flex; flex-direction:column; gap:16px;">
        <div class="card" style="padding:24px; border:1px solid var(--border); box-shadow:0 10px 15px -3px rgba(0,0,0,0.02);">
            <h3 style="font-size:12px; font-weight:800; color:var(--muted); text-transform:uppercase; letter-spacing:1.5px; margin-bottom:20px; display:flex; align-items:center; justify-content:space-between;">
                <div style="display:flex; align-items:center; gap:8px;">
                    <span style="width:4px; height:16px; background:var(--accent); border-radius:2px;"></span> Lead Snapshot
                </div>
                @if($lead->automation_stopped)
                    <span style="background:var(--danger); color:#fff; font-size:9px; padding:2px 6px; border-radius:4px;">REACH-OUT PAUSED</span>
                @endif
            </h3>
            
            <div style="display:grid; gap:20px;">
                {{-- Score & Stage Duration --}}
                <div style="display:flex; align-items:center; justify-content:space-between; background:rgba(37,99,235,0.03); padding:16px; border-radius:12px; border:1px solid rgba(16,185,129,0.1);">
                    <div>
                        <div style="font-size:11px; color:var(--muted); font-weight:700; text-transform:uppercase; margin-bottom:2px;">Commitment Level</div>
                        <div style="font-size:24px; font-weight:900; color:var(--accent);">{{ $lead->lead_score }}%</div>
                        <div style="font-size:10px; color:var(--success); font-weight:700;">ACTIVE IN FUNNEL</div>
                    </div>
                    <div style="width:50px; height:50px; border-radius:50%; background:conic-gradient(var(--accent) {{ $lead->lead_score }}%, #f1f5f9 0); display:flex; align-items:center; justify-content:center; box-shadow:0 2px 4px rgba(37,99,235,0.1);">
                        <div style="width:40px; height:40px; border-radius:50%; background:#fff; display:flex; align-items:center; justify-content:center; font-size:14px;">🔥</div>
                    </div>
                </div>

                {{-- Financial Snapshot --}}
                <div style="background:var(--surface2); padding:16px; border-radius:12px; border:1px solid var(--border);">
                    <div style="font-size:10px; color:var(--muted); font-weight:800; text-transform:uppercase; margin-bottom:12px; letter-spacing:0.5px; display:flex; justify-content:space-between;">
                        <span>💰 Financial Overview</span>
                        @if($lead->payment_status)
                        <span style="background:{{ $lead->payment_status === 'paid' ? 'var(--success)' : 'var(--gold)' }}; color:#fff; padding:1px 6px; border-radius:3px; font-size:9px;">{{ strtoupper($lead->payment_status) }}</span>
                        @endif
                    </div>
                    <div style="display:flex; justify-content:space-between; align-items:flex-end;">
                        <div>
                            <div style="font-size:10px; color:var(--muted); font-weight:700;">Total Value</div>
                            <div style="font-size:18px; font-weight:900; color:var(--text);">${{ number_format($lead->amount, 2) }}</div>
                        </div>
                        <div style="text-align:right;">
                            <div style="font-size:10px; color:var(--muted); font-weight:700;">Remaining Balance</div>
                            <div style="font-size:14px; font-weight:800; color:{{ ($lead->amount - $lead->advance_amount) > 0 ? 'var(--danger)' : 'var(--success)' }};">
                                ${{ number_format(max(0, $lead->amount - $lead->advance_amount), 2) }}
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Operational Info Group --}}
                <div style="display:grid; gap:12px;">
                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:32px; height:32px; background:var(--surface2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px;">⚡</div>
                        <div style="flex:1">
                            <div style="font-size:10px; color:var(--muted); font-weight:700; text-transform:uppercase;">Activity Pulse</div>
                            <div style="font-size:13px; font-weight:800; color:var(--text);">{{ $lead->followUps->count() + $lead->appointments->count() }} Total Interactions</div>
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:32px; height:32px; background:var(--surface2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px;">🤝</div>
                        <div style="flex:1">
                            <div style="font-size:10px; color:var(--muted); font-weight:700; text-transform:uppercase;">Primary Need</div>
                            <div style="font-size:13px; font-weight:800; color:var(--text);">{{ $lead->service ?? $lead->shades_needed ?? '--' }}</div>
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:32px; height:32px; background:var(--surface2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px;">👤</div>
                        <div style="flex:1">
                            <div style="font-size:10px; color:var(--muted); font-weight:700; text-transform:uppercase;">Assigned Agent</div>
                            <div style="font-size:13px; font-weight:800; color:var(--text);">{{ $lead->assigned_to ?: 'Core System' }}</div>
                        </div>
                    </div>

                    <div style="display:flex; align-items:center; gap:12px;">
                        <div style="width:32px; height:32px; background:var(--surface2); border-radius:8px; display:flex; align-items:center; justify-content:center; font-size:14px;">🔔</div>
                        <div style="flex:1">
                            <div style="font-size:10px; color:var(--muted); font-weight:700; text-transform:uppercase;">Last Outreach</div>
                            <div style="font-size:13px; font-weight:800; color:var(--text);">{{ $lead->last_sms_sent_at ? $lead->last_sms_sent_at->diffForHumans() : 'No contact yet' }}</div>
                        </div>
                    </div>
                </div>

                    <button type="button" onclick="openActionHub()" class="btn btn-primary" style="width:100%; justify-content:center; padding:12px; font-weight:800; border-radius:10px; margin-top:8px; display:flex; align-items:center; gap:8px; box-shadow:0 4px 6px -1px rgba(37,99,235,0.2);">
                        <span style="font-size:16px;">📨</span> Reach Out Now
                    </button>
                </div>

                {{-- Key Milestones (Upcoming) --}}
                <div style="padding-top:8px; border-top:1px dashed var(--border);">
                    <div style="font-size:10px; color:var(--muted); font-weight:800; text-transform:uppercase; margin-bottom:12px; letter-spacing:0.5px;">Upcoming Milestones</div>
                    
                    @php $hasMilestone = false; @endphp

                    @if($lead->next_follow_up)
                    @php $hasMilestone = true; @endphp
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <span style="font-size:12px; color:var(--text); font-weight:600;">🔔 Next Follow-up</span>
                        <span style="font-size:11px; background:rgba(210,153,34,0.08); color:var(--gold); padding:2px 8px; border-radius:4px; font-weight:800;">{{ $lead->next_follow_up->format('M d, h:i A') }}</span>
                    </div>
                    @endif

                    @if($lead->appointment_date)
                    @php $hasMilestone = true; @endphp
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <span style="font-size:12px; color:var(--text); font-weight:600;">📅 Site Visit</span>
                        <span style="font-size:11px; background:rgba(37,99,235,0.08); color:var(--accent); padding:2px 8px; border-radius:4px; font-weight:800;">{{ $lead->appointment_date->format('M d') }}</span>
                    </div>
                    @endif

                    @if($lead->install_date)
                    @php $hasMilestone = true; @endphp
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:8px;">
                        <span style="font-size:12px; color:var(--text); font-weight:600;">⚒ Installation</span>
                        <span style="font-size:11px; background:rgba(16,185,129,0.08); color:var(--success); padding:2px 8px; border-radius:4px; font-weight:800;">{{ $lead->install_date->format('M d') }}</span>
                    </div>
                    @endif

                    @if(!$hasMilestone)
                    <div style="font-size:11px; color:var(--muted); font-style:italic;">No milestones scheduled yet.</div>
                    @endif
                </div>

                {{-- Stats Sidebar Footer --}}
                <div style="display:flex; justify-content:space-between; font-size:11px; padding-top:12px; border-top:1px solid var(--border);">
                    <span style="color:var(--muted); font-weight:700;">ZIP: {{ $lead->zip_code ?: '--' }}</span>
                    <span style="color:var(--muted); font-weight:700;">UNITS: {{ $lead->windows_count ?: '0' }}</span>
                    <span style="color:var(--muted); font-weight:700;">AGE: {{ $lead->created_at?->diffInDays() }}D</span>
                </div>
            </div>
        </div>
            </div>
        </div>
        
        {{-- Static Card Removed - Moved to Modal --}}
    </div>
</div>

{{-- Action Hub Modal --}}
<div id="action-hub-modal" class="modal-overlay" onclick="closeActionHub()">
    <div class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 style="margin:0; font-size:16px; font-weight:800; color:var(--text); display:flex; align-items:center; gap:10px;">
                ⚡ Communication Hub
            </h3>
            <button onclick="closeActionHub()" style="background:none; border:none; color:var(--muted); font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <div class="modal-body">
            <form method="POST" action="{{ route('admin.leads.sms', $lead->id) }}">
                @csrf
                <div style="margin-bottom:20px;">
                    <label style="font-size:11px; font-weight:800; color:var(--muted); text-transform:uppercase; display:block; margin-bottom:8px;">Compose Message</label>
                    <div style="position:relative;">
                        <textarea class="form-control" name="message" rows="4" placeholder="Write your message here..." style="font-size:14px; border-radius:15px; border:1px solid var(--border); padding:16px; resize:none; background:#f8fafc;"></textarea>
                        <div style="position:absolute; bottom:12px; right:12px; font-size:10px; color:var(--muted); font-weight:700; display:flex; align-items:center; gap:4px;">
                            <span>AUTO-LOGGING</span> <div style="width:6px; height:6px; background:var(--success); border-radius:50%;"></div>
                        </div>
                    </div>
                </div>
                
                <div style="display:grid; grid-template-columns:1fr 1fr; gap:16px;">
                    <button type="submit" class="btn btn-primary" style="justify-content:center; padding:14px; font-weight:800; border-radius:12px; display:flex; align-items:center; gap:8px;">
                        <span style="font-size:18px;">📲</span> Send SMS
                    </button>
                    <a href="https://wa.me/{{ preg_replace('/[^0-9]/','',$lead->phone) }}" target="_blank" class="btn" style="background:#25d366; color:#fff; justify-content:center; padding:14px; font-weight:800; border-radius:12px; display:flex; align-items:center; gap:8px;">
                        <span style="font-size:18px;">💬</span> WhatsApp
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Message View Modal (Restore) --}}
<div id="msg-modal-overlay" class="modal-overlay" onclick="closeMsgPopup()">
    <div id="msg-modal" class="modal-content" onclick="event.stopPropagation()">
        <div class="modal-header">
            <h3 id="modal-title" style="margin:0; font-size:16px; font-weight:800; color:var(--text);">📬 Details</h3>
            <button onclick="closeMsgPopup()" style="background:none; border:none; color:var(--muted); font-size:24px; cursor:pointer;">&times;</button>
        </div>
        <div class="modal-body" id="modal-body" style="white-space: pre-wrap;"></div>
    </div>
</div>

<style>
    .tab-btn { background:none; border:none; padding:12px 20px; font-size:15px; font-weight:700; color:var(--muted); cursor:pointer; border-bottom:4px solid transparent; transition: all 0.2s; white-space: nowrap; }
    .tab-btn:hover { color:var(--text); background: var(--surface2); border-radius: 8px 8px 0 0; }
    .active-tab { color:var(--accent); border-bottom:4px solid var(--accent); background: rgba(37,99,235,0.05); border-radius: 8px 8px 0 0; }
    .form-label { font-size:13px; font-weight: 700; color:var(--muted); display:block; margin-bottom:8px; text-transform: uppercase; letter-spacing: 0.5px; }

    /* Timeline Styling */
    .timeline-container { position: relative; padding-left: 32px; border-left: 2px solid var(--border); margin-left: 10px; }
    .timeline-item { position: relative; margin-bottom: 32px; }
    .timeline-icon { position: absolute; left: -52px; width: 40px; height: 40px; border-radius: 50%; background: var(--surface); border: 2px solid var(--border); display: flex; align-items: center; justify-content: center; font-size: 16px; z-index: 2; box-shadow: var(--shadow); }
    .timeline-icon.call { border-color: var(--success); color: var(--success); }
    .timeline-icon.whatsapp { border-color: var(--accent); color: var(--accent); }
    .timeline-icon.email { border-color: var(--gold); color: var(--gold); }
    .timeline-content { background: var(--surface); border: 1px solid var(--border); padding: 16px; border-radius: 12px; transition: all .2s; }
    .timeline-content:hover { border-color: var(--accent); box-shadow: 0 4px 12px rgba(0,0,0,0.05); transform: translateX(4px); }
    
    .status-pill { appearance: none; border: 1px solid var(--border); background: var(--surface2); padding: 4px 12px; border-radius: 20px; font-size: 11px; font-weight: 800; text-transform: uppercase; cursor: pointer; transition: all .2s; }
    .status-pill.completed { background: #ecfdf5; color: var(--success); border-color: #10b981; }
    .status-pill.no_answer { background: #fef2f2; color: var(--danger); border-color: #ef4444; }
    .status-pill.pending { background: #eff6ff; color: var(--accent); border-color: #2563eb; }

    .btn-icon { background: var(--surface2); border: 1px solid var(--border); padding: 5px 8px; border-radius: 8px; cursor: pointer; transition: all 0.2s; font-size: 11px; }
    .btn-icon:hover { background: var(--accent); border-color: var(--accent); color: #fff; transform: scale(1.1); }
    .truncate-text { overflow: hidden; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical; }

    /* Premium Modal System */
    .modal-overlay { position: fixed; inset: 0; background: rgba(15,23,42,0.6); backdrop-filter: blur(8px); display: none; align-items: center; justify-content: center; z-index: 9999; padding: 20px; }
    .modal-content { background: #fff; width: 100%; max-width: 500px; border-radius: 24px; box-shadow: 0 25px 50px -12px rgba(0,0,0,0.25); border: 1px solid rgba(255,255,255,0.2); animation: modalIn .3s cubic-bezier(0.34, 1.56, 0.64, 1); overflow: hidden; }
    @keyframes modalIn { from { opacity: 0; transform: scale(0.95) translateY(10px); } to { opacity: 1; transform: scale(1) translateY(0); } }
    .modal-header { padding: 20px 24px; border-bottom: 1px solid var(--border); display: flex; justify-content: space-between; align-items: center; background: #fafafa; }
    .modal-body { padding: 24px; }

    /* Flatpickr Custom Theme */
    .flatpickr-calendar { font-family: 'Lato', sans-serif !important; border-radius: 12px !important; box-shadow: var(--shadow) !important; border: 1px solid var(--border) !important; padding: 5px; }
    .flatpickr-day.selected, .flatpickr-day.startRange, .flatpickr-day.endRange, .flatpickr-day.selected.continueSelection, .flatpickr-day.selected:hover, .flatpickr-day.startRange:hover, .flatpickr-day.endRange:hover, .flatpickr-day.selected.prevMonthDay, .flatpickr-day.selected.nextMonthDay, .flatpickr-day.startRange.prevMonthDay, .flatpickr-day.startRange.nextMonthDay, .flatpickr-day.endRange.prevMonthDay, .flatpickr-day.endRange.nextMonthDay { background: var(--accent) !important; border-color: var(--accent) !important; }
    .flatpickr-months .flatpickr-month { background: var(--surface) !important; color: var(--text) !important; fill: var(--text) !important; }
    .flatpickr-current-month .flatpickr-monthDropdown-months { font-weight: 800 !important; }
    .flatpickr-day.today { border-color: var(--gold) !important; }
</style>

<script>
    const LEAD_ID = '{{ $lead->id }}';

    function switchTab(tab) {
        document.querySelectorAll('.tab-btn').forEach(btn => btn.classList.remove('active-tab'));
        document.getElementById('tab-btn-' + tab).classList.add('active-tab');
        document.querySelectorAll('.tab-content').forEach(content => content.style.display = 'none');
        document.getElementById('tab-' + tab).style.display = 'block';

        // Remember tab for this specific lead
        localStorage.setItem('activeTab_' + LEAD_ID, tab);

        // Re-init flatpickr when tab becomes visible
        initPickers();
    }

    function initPickers() {
        flatpickr(".date-picker", {
            enableTime: true,
            dateFormat: "Y-m-d H:i",
            defaultDate: new Date(),
            allowInput: true
        });
        flatpickr(".date-picker-no-time", {
            enableTime: false,
            dateFormat: "Y-m-d",
            defaultDate: new Date(),
            allowInput: true
        });
    }

    // Initial load & Tab Persistence
    document.addEventListener('DOMContentLoaded', () => {
        initPickers();
        
        // Recover tab from LocalStorage
        const savedTab = localStorage.getItem('activeTab_' + LEAD_ID);
        if (savedTab && document.getElementById('tab-btn-' + savedTab)) {
            switchTab(savedTab);
        }
    });

    function updateActionFields(val) {
        document.getElementById('team-field').style.display = (val === 'installation') ? 'block' : 'none';
        document.getElementById('type-field').style.display = (val === 'installation') ? 'none' : 'block';
    }

    // Load Logs
    fetch('{{ route('admin.leads.logs', $lead->id) }}')
        .then(r => r.json())
        .then(logs => {
            const el = document.getElementById('activity-log');
            if(!el) return;
            el.innerHTML = logs.length ? logs.map(l => `<div style="padding:16px; background:var(--surface2); border:1px solid var(--border); border-radius:12px; margin-bottom:12px; transition:all 0.2s; position:relative; overflow:hidden;">
                <div style="position:absolute; left:0; top:0; bottom:0; width:4px; background:var(--accent); opacity:0.6;"></div>
                <div style="display:flex; justify-content:space-between; align-items:flex-start; margin-bottom:6px;">
                    <div style="font-weight:900; color:var(--text); font-size:14px; text-transform:uppercase; letter-spacing:0.5px;">${l.title}</div>
                    <div style="font-size:11px; font-weight:700; color:var(--muted); background:var(--surface); padding:2px 8px; border-radius:4px; border:1px solid var(--border);">${new Date(l.created_at).toLocaleString('en-US', { month:'short', day:'numeric', hour:'numeric', minute:'2-digit' })}</div>
                </div>
                <p style="color:var(--muted); margin:0; line-height:1.5; font-size:14px;">${l.notes || 'System action recorded.'}</p>
                <div style="margin-top:8px; font-size:10px; font-weight:800; color:var(--accent); display:flex; align-items:center; gap:4px;">
                    <span style="opacity:0.7;">BY</span> ${l.staff_name || 'SYSTEM CORE'}
                </div>
            </div>`).join('') : '<div style="text-align:center; padding:40px; color:var(--muted); opacity:0.5;"><div style="font-size:32px;">📜</div><p>No system logs found.</p></div>';
        });

    function openActionHub() { document.getElementById('action-hub-modal').style.display = 'flex'; }
    function closeActionHub() { document.getElementById('action-hub-modal').style.display = 'none'; }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert("✓ Proposal Sign Link copied to clipboard!");
        }).catch(err => {
            console.error('Failed to copy: ', err);
        });
    }

    function openMsgPopup(title, content) {
        document.getElementById('modal-title').innerText = title;
        document.getElementById('modal-body').innerHTML = content;
        document.getElementById('msg-modal-overlay').style.display = 'flex';
    }
    function closeMsgPopup() { document.getElementById('msg-modal-overlay').style.display = 'none'; }
</script>
@endsection
