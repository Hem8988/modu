<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use App\Models\Enquiry;
use App\Models\Customer;
use App\Models\Appointment;

class DashboardController extends Controller
{
    public function index()
    {
        $today = today();
        $thisMonth = now()->month;
        $thisYear = now()->year;

        // KPI Cards & Base Stats
        $totalLeads = Lead::count();
        $dealWonCount = Lead::where('status', 'deal_won')->count();
        
        $stats = [
            'total_leads'           => $totalLeads,
            'active_leads'          => Lead::whereNotIn('status', ['deal_won', 'lost', 'completed'])->count(),
            'today_leads'           => Lead::whereDate('created_at', $today)->count(),
            'conversion_rate'       => $totalLeads > 0 ? round(($dealWonCount / $totalLeads) * 100, 1) : 0,
            'revenue_month'         => Lead::whereMonth('created_at', $thisMonth)->whereYear('created_at', $thisYear)->where('status', 'deal_won')->sum('amount'),
            'total_converted_rev'   => Lead::where('status', 'deal_won')->sum('amount'),
            'pending_payments'      => \App\Models\Invoice::sum('due'),
            'follow_ups_today'      => Lead::whereDate('next_follow_up', $today)->count(),
            'installs_today'        => Lead::whereDate('install_date', $today)->count(),
            
            // Legacy/Mapping for compatibility
            'new'                   => Lead::where('status','new_lead')->count(),
            'contacted'             => Lead::where('status','contacted')->count(),
            'appointment'           => Lead::where('status','site_visit_scheduled')->count(),
            'deal_done'             => $dealWonCount,
            'lost'                  => Lead::where('status','lost')->count(),
            'customers'             => Customer::count(),
            'not_contacted'         => Lead::where('status', 'new_lead')->count(),
            'total_enquiry'         => Enquiry::count(),
            'upcoming_appointments' => Appointment::where('status','scheduled')->count(),
            'idle_leads'            => Lead::whereIn('status',['new_lead','contacted'])
                                           ->where('updated_at','<', now()->subDays(3))->count(),
        ];

        // ── Growth & Pipeline Funnel ──────────────────────────────────────────
        // Combined Enquiry + Lead funnel
        $totalEnquiries = Enquiry::count();
        
        $funnelStages = [
            'enquiry'              => ['label' => 'Enquiries',   'count' => $totalEnquiries],
            'new_lead'             => ['label' => 'New Leads',   'count' => Lead::where('status', 'new_lead')->count()],
            'contacted'            => ['label' => 'Contacted',   'count' => Lead::where('status', 'contacted')->count()],
            'site_visit_scheduled' => ['label' => 'Appointment', 'count' => Lead::where('status', 'site_visit_scheduled')->count()],
            'negotiation'          => ['label' => 'Negotiation', 'count' => Lead::where('status', 'negotiation')->count()],
            'deal_won'             => ['label' => 'Converted',   'count' => $dealWonCount],
        ];
        
        $funnelData = [];
        $previousCount = $totalEnquiries ?: 1;
        
        foreach($funnelStages as $stage => $data) {
            $count = $data['count'];
            $funnelData[] = [
                'stage'    => $data['label'],
                'count'    => $count,
                // Percent drop from previous stage
                'drop_off' => $previousCount > 0 ? round((1 - ($count / $previousCount)) * 100, 1) : 0
            ];
            $previousCount = $count ?: 1;
        }

        // ── Growth Insights & Forecasting ────────────────────────────────────
        $lastMonth = now()->subMonth();
        $leadsLastMonth = Lead::whereMonth('created_at', $lastMonth->month)->whereYear('created_at', $lastMonth->year)->count();
        $leadsThisMonth = Lead::whereMonth('created_at', $thisMonth)->whereYear('created_at', $thisYear)->count();
        
        $growthRate = $leadsLastMonth > 0 
            ? round((($leadsThisMonth - $leadsLastMonth) / $leadsLastMonth) * 100, 1) 
            : 100;

        // Revenue Forecast (Pipeline value * conversion rate)
        $pipelineValue = Lead::whereIn('status', ['site_visit_scheduled', 'measurement_done', 'quotation_sent', 'negotiation'])->sum('amount');
        $conversionRateNum = $stats['conversion_rate'] / 100;
        $revenueForecast = $pipelineValue * ($conversionRateNum ?: 0.15); // Default 15% if no conversion yet

        // Lead Quality (Avg Score)
        $avgLeadScore = round(Lead::whereIn('status', ['new_lead', 'contacted'])->get()->avg(fn($l) => $l->calculateScore()) ?? 0);

        $stats['growth_rate']      = $growthRate;
        $stats['revenue_forecast']  = $revenueForecast;
        $stats['total_pipeline']    = $pipelineValue;
        $stats['avg_lead_score']    = $avgLeadScore;

        // Action Panels
        $followUpsToday = Lead::whereDate('next_follow_up', $today)->orderBy('next_follow_up')->get();
        $visitsToday    = Appointment::with('lead')->where('type', 'site_visit')->whereDate('date', $today)->orderBy('time')->get();
        $installsToday  = Lead::whereDate('install_date', $today)->get();

        // Revenue & Performance Chart
        $revenueTrend = Lead::where('status', 'deal_won')
            ->selectRaw("DATE_FORMAT(created_at, '%b') as label, SUM(amount) as value")
            ->groupBy('label')
            ->orderByRaw("MIN(created_at)")
            ->limit(6)
            ->get();

        $dealsWeekly = Lead::where('status', 'deal_won')
            ->selectRaw("WEEK(created_at) as label, COUNT(*) as value")
            ->groupBy('label')
            ->orderBy('label')
            ->limit(5)
            ->get();

        // Lead Source Analytics
        $sources = Lead::selectRaw('source, count(*) as count')
            ->groupBy('source')
            ->orderByDesc('count')
            ->get()
            ->map(function($item) use ($dealWonCount) {
                $wonCount = Lead::where('source', $item->source)->where('status', 'deal_won')->count();
                $item->conversion = $item->count > 0 ? round(($wonCount / $item->count) * 100, 1) : 0;
                return $item;
            });

        // Sales Team Performance
        $teamPerformance = \App\Models\User::has('assignedLeads')
            ->withCount(['assignedLeads as deals_won' => function($q) {
                $q->where('status', 'deal_won');
            }])
            ->withSum(['assignedLeads as revenue' => function($q) {
                $q->where('status', 'deal_won');
            }], 'amount')
            ->get()
            ->sortByDesc('revenue');

        // Installation Tracker
        $installStats = [
            'pending'   => Lead::where('status', 'installation_pending')->count(),
            'progress'  => Lead::where('status', 'negotiation')->count(), // Using negotiation as proxy if in_progress not used
            'completed' => Lead::where('status', 'completed')->whereDate('updated_at', $today)->count()
        ];

        // Payment Snapshot
        $payments = [
            'advance' => Lead::sum('advance_amount'),
            'pending' => \App\Models\Invoice::sum('due'),
            'overdue' => \App\Models\Invoice::where('due', '>', 0)->where('due_date', '<', $today)->sum('due')
        ];

        // Alerts
        $alerts = [
            'not_contacted'     => Lead::where('status', 'new_lead')->where('updated_at', '<', now()->subHours(24))->count(),
            'quotes_pending'    => Lead::where('status', 'quotation_sent')->where('updated_at', '<', now()->subDays(2))->count(),
            'idle_leads'        => $stats['idle_leads'],
        ];

        // ── Point 12: Installation Tracker ──────────────────────────────────
        $installStats = [
            'pending'   => \App\Models\Installation::where('status', 'pending')->count(),
            'progress'  => \App\Models\Installation::where('status', 'in_progress')->count(),
            'completed' => \App\Models\Installation::where('status', 'completed')->count(),
            'today'     => \App\Models\Installation::whereDate('date', $today)->count(),
        ];
        
        $stats['installations_count'] = $installStats['today'];

        // ── Point 11: Payment Management ─────────────────────────────────────
        $payments = [
            'advance'           => \App\Models\Payment::where('notes', 'LIKE', '%advance%')->sum('amount'),
            'pending'           => \App\Models\Invoice::sum('due'),
            'overdue'           => \App\Models\Invoice::where('due_date', '<', $today)->where('due', '>', 0)->sum('due'),
            'overdue_count'      => \App\Models\Invoice::where('due_date', '<', $today)->where('due', '>', 0)->count(),
        ];
        $stats['overdue_invoices_count'] = $payments['overdue_count'];
        $stats['pending_payments']       = $payments['pending'];

        // ── Sidebar Stage Monitoring ─────────────────────────────────────────
        $sidebarCounts = [
            'Enquiries'      => \App\Models\Enquiry::count(),
            'New Lead'       => Lead::where('status', 'new_lead')->count(),
            'Contacted'      => Lead::where('status', 'contacted')->count(),
            'Appt Scheduled' => Lead::where('status', 'site_visit_scheduled')->count(),
            'Negotiation'    => Lead::where('status', 'negotiation')->count(),
            'Won'            => Lead::where('status', 'deal_won')->count(),
            'Lost'           => Lead::where('status', 'lost')->count(),
            'feedback'       => \App\Models\Feedback::count(),
            'open_complaints'=> \App\Models\Complaint::where('status', 'open')->count(),
            'service_requests' => \App\Models\ServiceRequest::count(),
            'resolutions'    => \App\Models\Resolution::count(),
        ];

        // ── Point 14 & 17: Strategic Hub & Proactive Alerts ─────────────────
        $notContactedCount = Lead::where('status', 'new_lead')->where('updated_at', '<', now()->subHours(24))->count();
        $quotesPendingCount = Lead::where('status', 'site_visit_scheduled')->whereDoesntHave('quotes')->count();

        $alerts = [
            'not_contacted'  => $notContactedCount,
            'quotes_pending' => $quotesPendingCount,
            'idle_leads'     => Lead::whereIn('status', ['new_lead', 'contacted'])->where('updated_at', '<', now()->subDays(3))->count(),
            'list'           => [] // Dynamic list for messages in other slots
        ];
        
        // 1. Mission Mandate: 48hr Conversion
        $alerts['list'][] = ['type' => 'success', 'msg' => "AI INSIGHT: Optimize conversion by prioritizing site visits within 48 hours (+18% win rate mandate)."];

        if ($notContactedCount > 0) {
            $alerts['list'][] = ['type' => 'danger', 'msg' => "VELOCITY ALERT: {$notContactedCount} Leads cooling (24h+ silence). Dispatch required."];
        }

        if ($quotesPendingCount > 0) {
            $alerts['list'][] = ['type' => 'info', 'msg' => "BIDDING ALERT: {$quotesPendingCount} Project proposals pending. Blueprint required."];
        }

        if ($payments['overdue_count'] > 0) {
            $alerts['list'][] = ['type' => 'danger', 'msg' => "TREASURY ALERT: {$payments['overdue_count']} Invoices Overdue. Recovery pulse active."];
        }

        // ── Point 13: Sales Team Performance ────────────────────────────────
        $teamPerformance = \App\Models\User::where('role', 'staff')
            ->get()
            ->map(function($user) {
                $user->deals_won = Lead::where('assigned_to', $user->id)->where('status', 'deal_won')->count();
                $user->revenue   = Lead::where('assigned_to', $user->id)->where('status', 'deal_won')->sum('amount');
                return $user;
            })->sortByDesc('revenue');

        // ── Step 17: Revenue Trend Analytics ───────────────────────────────
        $revenueTrend = [];
        for ($i = 5; $i >= 0; $i--) {
            $monthDate = now()->subMonths($i);
            $revenueTrend[] = [
                'label' => $monthDate->format('M'),
                'value' => Lead::whereMonth('created_at', $monthDate->month)->whereYear('created_at', $monthDate->year)->where('status', 'deal_won')->sum('amount')
            ];
        }

        $recentActivity = \App\Models\ActivityLog::with('lead')->latest()->take(10)->get();

        return view('admin.dashboard', compact(
            'stats', 
            'funnelData', 
            'followUpsToday', 
            'visitsToday', 
            'installsToday', 
            'installStats',
            'sidebarCounts',
            'payments',
            'alerts',
            'teamPerformance',
            'revenueTrend',
            'recentActivity'
        ));
    }
}
