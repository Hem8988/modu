<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Customer;
use App\Models\Appointment;
use App\Models\ActivityLog;
use App\Models\FollowUp;
use App\Models\Installation;
use App\Services\TwilioService;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    public function index(Request $request)
    {
        $query = Lead::query()->latest();
        if ($s = $request->status) {
            $map = [
                'new_lead'             => ['new', 'new_lead', 'nfw'],
                'site_visit_scheduled' => ['site_visit_scheduled', 'measurement_done', 'appointment', 'appt'],
                'quotation_sent'       => ['quotation_sent', 'quote_sent'],
                'invoice_sent'         => ['invoice_sent', 'proforma_sent'],
                'negotiation'          => ['negotiation', 'discussion'],
                'deal_won'             => ['deal_won', 'won', 'completed', 'converted', 'done'],
            ];
            $query->whereIn('status', $map[$s] ?? [$s]);
        }
        if ($q = $request->search) $query->where(fn($b) => $b->where('name','like',"%$q%")->orWhere('phone','like',"%$q%")->orWhere('email','like',"%$q%"));
        if ($src = $request->source) $query->where('source', $src);
        if ($f = $request->from_date) $query->whereDate('created_at', '>=', $f);
        if ($t = $request->to_date)   $query->whereDate('created_at', '<=', $t);

        $counts = [
            'all'                  => Lead::count(),
            'new_lead'             => Lead::whereIn('status', ['new', 'new_lead'])->count(),
            'contacted'            => Lead::whereIn('status', ['contacted'])->count(),
            'site_visit_scheduled' => Lead::whereIn('status', ['site_visit_scheduled', 'measurement_done', 'appointment'])->count(),
            'quotation_sent'       => Lead::whereIn('status', ['quotation_sent', 'quote_sent'])->count(),
            'invoice_sent'         => Lead::whereIn('status', ['invoice_sent', 'proforma_sent'])->count(),
            'negotiation'          => Lead::whereIn('status', ['negotiation', 'discussion'])->count(),
            'deal_won'             => Lead::whereIn('status', ['deal_won', 'won', 'completed', 'converted'])->count(),
            'lost'                 => Lead::whereIn('status', ['lost'])->count(),
        ];

        return view('admin.leads.index', [
            'leads' => $query->paginate(20), 
            'status_filter' => $request->status ?? 'all',
            'counts' => $counts
        ]);
    }

    public function show($id)
    {
        $lead     = Lead::with(['followUps','appointments','installations'])->findOrFail($id);
        $customer = Customer::where('lead_id', $id)->first();
        $invoices = $customer ? $customer->invoices()->latest()->get() : collect();
        return view('admin.leads.show', compact('lead','customer','invoices'));
    }

    public function store(Request $request)
    {
        $data = $request->only(['name','email','phone','zip_code','shades_needed','windows_count','budget','amount','service','source','city','address','campaign']);
        $data['status'] = 'new_lead';
        $data['source'] = $data['source'] ?? 'Manual Entry';
        $lead = Lead::create($data);
        $lead->lead_score = $lead->calculateScore();
        $lead->save();
        ActivityLog::log($lead->id, 'Lead Created', 'Manually added with score: '.$lead->lead_score);
        return redirect($request->source === 'Manual Quote Page' ? route('admin.quotations.index') : route('admin.leads.index'))->with('success','Lead created.');
    }

    public function update(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $old  = $lead->status;
        
        // Explicitly handle status update for reliability
        if ($request->has('status')) {
            $lead->status = $request->status;
        }

        $lead->fill($request->except(['_token','_method','status']));
        $lead->lead_score = $lead->calculateScore();
        $lead->save();

        if ($old !== $lead->status) {
            $msg = "Vellora Status advanced: " . strtoupper(str_replace('_',' ',$old)) . " ➔ " . strtoupper(str_replace('_',' ',$lead->status));
            ActivityLog::log($id, 'Pipeline Advanced', $msg);
            
            // Automatic Customer Registration Protocol
            if (in_array($lead->status, ['deal_won', 'won', 'converted'])) {
                $this->syncCustomer($id);
            }
        } else {
            ActivityLog::log($id, 'Lead Updated', $request->feedback ?? 'Lead specifications refined.');
        }

        return back()->with('success', '✓ Lead successfully updated.');
    }

    public function destroy($id)
    {
        Lead::destroy($id);
        return redirect()->route('admin.leads.index')->with('success','Lead deleted.');
    }

    public function advanceStatus($id)
    {
        $lead   = Lead::findOrFail($id);
        $stages = ['new_lead', 'contacted', 'site_visit_scheduled', 'negotiation', 'deal_won'];
        $idx    = array_search($lead->status, $stages);
        
        if ($idx === false) $next = 'new_lead';
        elseif ($idx >= count($stages) - 1) return back()->with('info', 'Lead reached final stage.');
        else $next = $stages[$idx + 1];

        $lead->update(['status' => $next]);
        ActivityLog::log($id, 'Pipeline Advanced', 'Status updated to ' . ucfirst(str_replace('_', ' ', $next)));
        if ($next === 'deal_won') $this->syncCustomer($id);
        return back()->with('success', 'Lead moved to ' . ucfirst(str_replace('_', ' ', $next)));
    }

    public function addAction(Request $request, $id)
    {
        $lead = Lead::findOrFail($id);
        $type = $request->action_type;
        $dt   = \Carbon\Carbon::parse($request->date);
        
        $data = ['lead_id' => $id, 'date' => $dt, 'notes' => $request->notes];

        if ($type === 'follow_up') {
            $model = \App\Models\FollowUp::class;
            $data['type'] = $request->type ?? 'call';
            $data['status'] = $request->status ?? 'pending';
            $lead->update(['next_follow_up' => $dt]);
        } elseif ($type === 'appointment') {
            $model = Appointment::class;
            $data['type'] = $request->type ?? 'measurement';
            $data['time'] = $dt->toTimeString();
            $data['date'] = $dt->toDateString();
            $data['status'] = $request->status ?? 'scheduled';
            $lead->update(['appointment_date' => $dt, 'status' => 'site_visit_scheduled']);
        } elseif ($type === 'installation') {
            $model = Installation::class;
            $data['team'] = $request->team;
            $data['date'] = $dt->toDateString();
            $data['status'] = $request->status ?? 'scheduled';
            $lead->update(['install_date' => $dt]);
        }

        ActivityLog::log($id, 'Action Added', 'New '.str_replace('_',' ',$type).' scheduled for '.$dt->format('M d, h:i A'));
        $model::create($data);

        return back()->with('success', 'Action recorded and history updated.');
    }

    public function updateAction(Request $request, $leadId, $type, $id)
    {
        $status = $request->status;
        $title  = "";

        switch ($type) {
            case 'follow':
                $action = \App\Models\FollowUp::findOrFail($id);
                $title  = "Follow-up Outreach";
                break;
            case 'appt':
                $action = \App\Models\Appointment::findOrFail($id);
                $title  = "Site Appointment";
                break;
            case 'install':
                $action = \App\Models\Installation::findOrFail($id);
                $title  = "Window Installation";
                break;
            default:
                return back()->with('error', 'Invalid action type.');
        }

        $oldStatus = $action->status;

        // High-Fidelity Schema & Outcome Mapping
        $finalStatus = $status;
        if ($type === 'appt' && $status === 'pending') $finalStatus = 'scheduled';
        if ($type === 'install' && $status === 'pending') $finalStatus = 'scheduled';
        // Executive Audit Log
        if ($type === 'follow' && $status === 'no_answer' && $oldStatus !== 'no_answer') {
            $action->notes = ($action->notes ? $action->notes . " | " : "") . "[Call Attempted - No Answer]";
            $action->save();
        }

        $action->update(['status' => $status]);
        
        // Executive Audit Log
        ActivityLog::log($leadId, "{$title} Updated", "Status changed from {$oldStatus} to {$status} for record #{$id}.");

        return back()->with('success', "✓ {$title} status updated to " . strtoupper($status) . ".");
    }

    public function sendSms(Request $request, $id, TwilioService $twilio)
    {
        $lead = Lead::findOrFail($id);
        $msg  = $request->validate(['message' => 'required'])['message'];
        $twilio->send($lead->phone, $msg);
        ActivityLog::log($id, 'SMS Sent', 'Manual: '.$msg);
        return back()->with('success','SMS sent.');
    }

    public function logs($id)
    {
        return response()->json(ActivityLog::where('lead_id',$id)->latest()->get());
    }

    public function pipeline()
    {
        $stages = [
            'new_lead'               => 'New Enquiries',
            'contacted'              => 'Qualified / Contacted',
            'site_visit_scheduled'   => 'Site Visit Scheduled',
            'measurement_done'       => 'Measurement Done',
            'quotation_sent'         => 'Bidding Hub',
            'negotiation'            => 'Negotiation',
            'deal_won'               => 'Won / Conversion',
            'installation_pending'   => 'Installation Pending',
            'completed'              => 'Project Completed'
        ];

        $leadsByStage = [];
        foreach ($stages as $key => $label) {
            $leadsByStage[$key] = [
                'label' => $label,
                'leads' => Lead::where('status', $key)->latest()->get(),
                'count' => Lead::where('status', $key)->count(),
                'value' => Lead::where('status', $key)->sum('amount')
            ];
        }

        return view('admin.leads.pipeline', compact('leadsByStage', 'stages'));
    }

    private function syncCustomer($leadId)
    {
        $lead = Lead::find($leadId);
        if (!$lead) return;
        $existing = Customer::where('lead_id',$leadId)->first();
        $data = ['lead_id'=>$leadId,'name'=>$lead->name,'phone'=>$lead->phone,'email'=>$lead->email,'address'=>$lead->address,'project'=>$lead->service??$lead->shades_needed,'source'=>$lead->source];
        $existing ? $existing->update($data) : Customer::create(array_merge($data,['converted_date'=>now()->toDateString()]));
    }
}
