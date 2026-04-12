@extends('layouts.admin')
@section('title', 'Sales Pipeline')
@section('content')
<style>
    :root { 
        --pipe-bg: #0f172a; --pipe-surface: #1e293b; --pipe-border: rgba(255,255,255,0.05);
        --pipe-text: #f8fafc; --pipe-muted: #94a3b8; 
    }
    
    body { background: var(--pipe-bg) !important; color: var(--pipe-text) !important; }
    .main-content { background: var(--pipe-bg); }
    .topbar { background: var(--pipe-surface); border-bottom: 1px solid var(--pipe-border); color: #fff; }
    .topbar-title { color: #fff; }

    .pipeline-container { display: flex; gap: 16px; overflow-x: auto; padding: 20px; min-height: calc(100vh - 120px); }
    .pipeline-stage { flex: 0 0 320px; background: rgba(30, 41, 59, 0.4); border-radius: 12px; display: flex; flex-direction: column; max-height: calc(100vh - 160px); border: 1px solid var(--pipe-border); backdrop-filter: blur(10px); }
    
    .stage-header { padding: 18px; border-bottom: 1px solid var(--pipe-border); display: flex; justify-content: space-between; align-items: center; border-radius: 12px 12px 0 0; background: rgba(30, 41, 59, 0.6); }
    .stage-label { font-size: 12px; font-weight: 800; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; }
    .stage-meta { font-size: 11px; color: var(--pipe-muted); background: rgba(255,255,255,0.05); padding: 2px 8px; border-radius: 20px; font-weight: 700; }
    
    .stage-leads { flex: 1; overflow-y: auto; padding: 12px; display: flex; flex-direction: column; gap: 12px; }
    
    .lead-card { background: var(--pipe-surface); border-radius: 10px; padding: 16px; border: 1px solid var(--pipe-border); transition: all .3s cubic-bezier(0.4, 0, 0.2, 1); text-decoration: none; color: inherit; display: block; position: relative; overflow: hidden; }
    .lead-card:hover { transform: translateY(-3px) scale(1.02); box-shadow: 0 20px 40px rgba(0,0,0,0.4); border-color: var(--gold); }
    .lead-card::before { content: ''; position: absolute; top: 0; left: 0; width: 4px; height: 100%; background: var(--gold); opacity: 0; transition: opacity .3s; }
    .lead-card:hover::before { opacity: 1; }
    
    .lead-id { font-size: 9px; font-weight: 800; color: var(--gold); text-transform: uppercase; margin-bottom: 6px; letter-spacing: 1px; opacity: 0.8; }
    .lead-name { font-size: 15px; font-weight: 700; color: #fff; margin-bottom: 10px; }
    .lead-info { display: flex; justify-content: space-between; align-items: center; font-size: 11px; margin-bottom: 12px; }
    .lead-score { border: 1px solid rgba(184, 155, 94, 0.3); color: var(--gold); padding: 2px 10px; border-radius: 4px; font-weight: 800; font-size: 10px; }
    .lead-amount { font-weight: 800; font-size: 14px; color: var(--pipe-text); }
    
    .lead-footer { border-top: 1px solid var(--pipe-border); padding-top: 10px; display: flex; justify-content: space-between; align-items: center; font-size: 10px; color: var(--pipe-muted); font-weight: 600; }
    
    .pipeline-container::-webkit-scrollbar { height: 8px; }
    .pipeline-container::-webkit-scrollbar-thumb { background: rgba(184, 155, 94, 0.2); border-radius: 10px; }
    .pipeline-container::-webkit-scrollbar-track { background: var(--pipe-bg); }

    .stage-value { border-top: 1px solid var(--pipe-border); padding: 15px; text-align: right; font-size: 14px; font-weight: 800; color: #fff; background: rgba(30, 41, 59, 0.6); border-radius: 0 0 12px 12px; }
    .stage-value span { font-size: 9px; font-weight: 800; color: var(--gold); text-transform: uppercase; letter-spacing: 1px; }
</style>

<div class="pipeline-container">
    @foreach($leadsByStage as $key => $data)
    <div class="pipeline-stage" data-stage="{{ $key }}">
        <div class="stage-header">
            <div class="stage-label">{{ $data['label'] }}</div>
            <div class="stage-meta" id="count-{{ $key }}">{{ $data['count'] }}</div>
        </div>
        
        <div class="stage-leads sortable-stage" id="stage-{{ $key }}">
            @foreach($data['leads'] as $lead)
            <a href="{{ route('admin.leads.show', $lead->id) }}" class="lead-card" data-id="{{ $lead->id }}">
                <div class="lead-id">#{{ $lead->id }} • {{ strtoupper($lead->source) }}</div>
                <div class="lead-name">{{ $lead->name }}</div>
                
                <div class="lead-info">
                    <div class="lead-score">{{ $lead->lead_score }} PULSE</div>
                    <div class="lead-amount" data-val="{{ $lead->amount }}">${{ number_format($lead->amount, 0) }}</div>
                </div>
                
                <div class="lead-footer">
                    <div><i class="far fa-clock" style="color: var(--gold)"></i> {{ $lead->updated_at->diffForHumans() }}</div>
                    @if($lead->windows_count > 0)
                    <div style="color: #fff">🪟 {{ $lead->windows_count }} SITE UNTS</div>
                    @endif
                </div>
            </a>
            @endforeach
        </div>
        
        <div class="stage-value">
            <span>Stage Treasury</span><br>
            $<span id="value-{{ $key }}">{{ number_format($data['value'], 0) }}</span>
        </div>
    </div>
    @endforeach
</div>

<script src="https://cdn.jsdelivr.net/npm/sortablejs@1.15.0/Sortable.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const stages = document.querySelectorAll('.sortable-stage');
    
    stages.forEach(stage => {
        new Sortable(stage, {
            group: 'pipeline',
            animation: 250,
            ghostClass: 'sortable-ghost',
            chosenClass: 'sortable-chosen',
            dragClass: 'sortable-drag',
            onEnd: function(evt) {
                const leadId = evt.item.dataset.id;
                const newStage = evt.to.closest('.pipeline-stage').dataset.stage;
                const oldStage = evt.from.closest('.pipeline-stage').dataset.stage;

                if (newStage === oldStage) return;

                // Executive Registry Update Pulse
                fetch(`{{ url('admin/leads') }}/${leadId}`, {
                    method: 'PUT',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ status: newStage, feedback: 'Automated Pipeline Dispatch' })
                })
                .then(res => res.json())
                .catch(err => console.error('Treasury Sync Error:', err));

                // Recalculate Totals (Industrial Logic)
                recalculateStage(evt.from.closest('.pipeline-stage'));
                recalculateStage(evt.to.closest('.pipeline-stage'));
            }
        });
    });

    function recalculateStage(stageEl) {
        const stageKey = stageEl.dataset.stage;
        const leads = stageEl.querySelectorAll('.lead-card');
        const countEl = stageEl.querySelector('.stage-meta');
        const valueEl = stageEl.querySelector('.stage-value span:last-child');
        
        let totalValue = 0;
        leads.forEach(l => {
            const val = parseFloat(l.querySelector('.lead-amount').dataset.val) || 0;
            totalValue += val;
        });

        countEl.textContent = leads.length;
        valueEl.textContent = totalValue.toLocaleString();
    }
});
</script>

<style>
    .sortable-ghost { opacity: 0.2; transform: scale(0.9); }
    .sortable-chosen { border-color: var(--gold) !important; background: rgba(184, 155, 94, 0.1) !important; }
    .sortable-drag { opacity: 0.8; }
</style>
@endsection
