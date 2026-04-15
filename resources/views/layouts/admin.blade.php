<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $globalSettings['site_name'] ?? 'ModuShade CRM' }} | @yield('title', 'Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Lato:wght@300;400;700;900&display=swap');
        :root {
            --bg: #f8fafc; --surface: #ffffff; --surface2: #f1f5f9;
            --border: #e2e8f0; --accent: #2563eb; --gold: #b89b5e;
            --text: #1e293b; --muted: #64748b; --success: #10b981;
            --danger: #ef4444; --warning: #f59e0b;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Lato', sans-serif; background: var(--bg); color: var(--text); min-height: 100vh; display: flex; -webkit-font-smoothing: antialiased; font-size: 14px; line-height: 1.5; }
        
        /* Sidebar Redesign */
        .sidebar { width: 300px; background: var(--surface); border-right: 1px solid var(--border); display: flex; flex-direction: column; padding: 0; position: fixed; height: 100vh; overflow-y: auto; z-index: 1000; }
        .sidebar-header { padding: 24px 20px; text-align: center; border-bottom: 1px solid var(--border); }
        .sidebar-logo { font-size: 24px; font-weight: 900; color: var(--accent); text-decoration: none; letter-spacing: -0.5px; }
        .sidebar-logo span { color: var(--gold); }
        .sidebar-nav { padding: 16px 12px; flex: 1; }
        .nav-section { font-size: 14px; font-weight: 700; color: var(--muted); text-transform: uppercase; letter-spacing: 1.5px; padding: 24px 12px 12px; }
        .nav-link { display: flex; align-items: center; gap: 12px; padding: 10px 16px; border-radius: 10px; color: var(--muted); text-decoration: none; font-size: 14px; font-weight: 500; transition: all .2s ease; margin-bottom: 4px; }
        .nav-link:hover { background: var(--surface2); color: var(--accent); }
        .nav-link.active { background: rgba(37, 99, 235, 0.08); color: var(--accent); font-weight: 600; }
        
        /* Layout Structure */
        .main-content { flex: 1; margin-left: 300px; min-height: 100vh; display: flex; flex-direction: column; }
        .topbar { background: var(--surface); border-bottom: 1px solid var(--border); padding: 0 32px; height: 64px; display: flex; justify-content: space-between; align-items: center; position: sticky; top: 0; z-index: 100; box-shadow: 0 1px 2px rgba(0,0,0,0.05); }
        .topbar-title { font-size: 20px; font-weight: 800; color: var(--text); letter-spacing: -0.5px; }
        .content { padding: 32px; flex: 1; }
        
        /* Reusable Components */
        .card { background: var(--surface); border: 1px solid var(--border); border-radius: 12px; padding: 24px; margin-bottom: 24px; box-shadow: var(--shadow); }
        .card-title { font-size: 22px; font-weight: 700; margin-bottom: 24px; color: var(--text); border-bottom: 2px solid var(--border); padding-bottom: 16px; letter-spacing: -0.3px; }
        
        table { width: 100%; border-collapse: collapse; }
        th { color: var(--muted); text-align: left; padding: 16px 20px; border-bottom: 2px solid var(--border); font-weight: 700; font-size: 14px; text-transform: uppercase; letter-spacing: 1px; background: var(--surface2); }
        td { padding: 14px 20px; border-bottom: 1px solid var(--border); font-size: 14px; color: var(--text); font-weight: 500; }
        tr:hover td { background: rgba(37, 99, 235, 0.02); }
        
        .badge { display: inline-flex; align-items: center; padding: 4px 10px; border-radius: 6px; font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 0.5px; }
        .badge-new        { background: #dbeafe; color: #1d4ed8; }
        .badge-contacted  { background: #fef3c7; color: #b45309; }
        .badge-deal_won  { background: #d1fae5; color: #047857; }
        .badge-lost       { background: #fee2e2; color: #b91c1c; }
        .badge-appointment{ background: #ede9fe; color: #6d28d9; }
        
        .btn { display: inline-flex; align-items: center; gap: 8px; padding: 10px 20px; border-radius: 10px; font-size: 14px; font-weight: 700; cursor: pointer; border: none; text-decoration: none; transition: all .2s; }
        .btn-primary { background: var(--accent); color: white; box-shadow: 0 4px 10px rgba(37, 99, 235, 0.2); }
        .btn-primary:hover { background: #1d4ed8; transform: translateY(-1px); }
        .btn-danger  { background: #fee2e2; color: var(--danger); border: 1px solid #fca5a5; }
        .btn-sm { padding: 8px 14px; font-size: 12px; }

        .form-control { width: 100%; background: #ffffff; border: 1.5px solid var(--border); color: var(--text); padding: 10px 14px; border-radius: 8px; font-size: 14px; transition: all .2s; font-family: inherit; }
        .form-control:focus { outline: none; border-color: var(--accent); box-shadow: 0 0 0 4px rgba(37, 99, 235, 0.1); background: #fff; }
        
        .alert { border-radius: 12px; padding: 20px; margin-bottom: 32px; font-weight: 600; font-size: 14px; border: 1px solid transparent; box-shadow: 0 2px 4px rgba(0,0,0,0.05); }
        .alert-success { background: #ecfdf5; border-color: #10b981; color: #065f46; }
    </style>
</head>
<body>
<aside class="sidebar">
    <div class="sidebar-header">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            <span>MODU</span>SHADE <small style="font-size:16px;color:var(--muted);font-weight:400;margin-left:4px">CRM</small>
        </a>
    </div>
    <nav class="sidebar-nav">
        <!-- SALES HUB -->
        <div class="nav-section">Sales Operations</div>
        <a href="{{ route('admin.enquiries.index') }}" class="nav-link {{ request()->routeIs('admin.enquiries*') ? 'active' : '' }}">
            <i class="fas fa-inbox"></i> 📝 Enquiries
        </a>
        <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads*') && !request('status') ? 'active' : '' }}">
            <i class="fas fa-bullseye"></i> 🎯 Leads Registry
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'new_lead']) }}" class="nav-link {{ request('status') == 'new_lead' ? 'active' : '' }}">
            🎯 New Leads <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['New Lead'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'contacted']) }}" class="nav-link {{ request('status') == 'contacted' ? 'active' : '' }}">
            📻 Contacted <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['Contacted'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'site_visit_scheduled']) }}" class="nav-link {{ request('status') == 'site_visit_scheduled' ? 'active' : '' }}">
            📅 Appointment <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['Appt Scheduled'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'quotation_sent']) }}" class="nav-link {{ request('status') == 'quotation_sent' ? 'active' : '' }}">
            📄 Quotation Sent <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['Quotation Sent'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'invoice_sent']) }}" class="nav-link {{ request('status') == 'invoice_sent' ? 'active' : '' }}">
            🧾 Invoice Sent <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['Invoice Sent'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'negotiation']) }}" class="nav-link {{ request('status') == 'negotiation' ? 'active' : '' }}">
            🤝 Negotiation <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['Negotiation'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'deal_won']) }}" class="nav-link {{ request('status') == 'deal_won' ? 'active' : '' }}">
            🏆 Deal Won <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['Won'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'lost']) }}" class="nav-link {{ request('status') == 'lost' ? 'active' : '' }}">
            ❌ Lost <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['Lost'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.pipeline') }}" class="nav-link {{ request()->routeIs('admin.leads.pipeline') ? 'active' : '' }}">
            <i class="fas fa-stream"></i> 🔀 Sales Pipeline
        </a>

        <!-- OPERATIONS REGISTRY -->
        <div class="nav-section" style="margin-top:15px;">Execution & Ops</div>
        <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
            <i class="fas fa-calendar-alt"></i> 🗓 Field Appointments
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <i class="fas fa-users"></i> 👥 Customers
        </a>
        <a href="{{ route('admin.installations.index') }}" class="nav-link {{ request()->routeIs('admin.installations*') ? 'active' : '' }}">
            <i class="fas fa-tools"></i> 🛠 Installations
        </a>
        <a href="{{ route('admin.complaints.index') }}" class="nav-link {{ request()->routeIs('admin.complaints*') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle"></i> ⚠ Feedback Hub
        </a>
        <a href="{{ route('admin.complaints.index', ['tab' => 'feedback']) }}" class="nav-link">
            💬 Feedback <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['feedback'] ?? \App\Models\Feedback::count() }}</span>
        </a>
        <a href="{{ route('admin.complaints.index', ['tab' => 'complaints']) }}" class="nav-link">
            🏮 Complaints <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['open_complaints'] ?? \App\Models\Complaint::where('status', 'open')->count() }}</span>
        </a>
        <a href="{{ route('admin.complaints.index', ['tab' => 'service']) }}" class="nav-link">
            ⚙ Service <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['service_requests'] ?? \App\Models\ServiceRequest::count() }}</span>
        </a>
        <a href="{{ route('admin.complaints.index', ['tab' => 'resolutions']) }}" class="nav-link">
            ✅ Resolutions <span style="margin-left:auto; opacity:0.7">{{ $sidebarCounts['resolutions'] ?? \App\Models\Resolution::count() }}</span>
        </a>

        <!-- FINANCE COMMAND -->
        <div class="nav-section" style="margin-top:15px;">Finance & Bidding</div>
        <a href="{{ route('admin.quotations.index') }}" class="nav-link {{ request()->routeIs('admin.quotations*') ? 'active' : '' }}">
            📄 Project Proposals
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            📦 Product Registry
        </a>
        <a href="{{ route('admin.products.index') }}#add" class="nav-link" style="padding-left: 40px; font-size: 13px; opacity: 0.8;">
            ✨ Add New Product
        </a>
        <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ request()->routeIs('admin.invoices*') ? 'active' : '' }}">
            🧾 Invoices
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            💰 Payments Pulse
        </a>

        <!-- REPORTS & ANALYTICS -->
        <div class="nav-section" style="margin-top:15px;">Intelligence Hub</div>
        <a href="#" class="nav-link">
            📈 Performance Reports
        </a>

        <!-- SETTINGS -->
        <div class="nav-section" style="margin-top:15px;">System Config</div>
        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            ⚙ Global Settings
        </a>


        <div style="margin-top:20px; padding-top:20px; border-top:1px solid var(--border)">
            <div style="font-size:17px; font-weight:700; color:var(--muted); text-transform:uppercase; letter-spacing:1px; margin-bottom:8px">OFFICIAL REGISTRY</div>
            <div style="font-size:18px; font-weight:600; color:#000; line-height:1.5;">
                24 Poplar Street<br>
                Creskill, NJ 07626<br>
                <div style="color:var(--accent); margin-top:4px; font-weight:800; font-size:20px;">+1 201 660 5298</div>
            </div>
        </div>

        <div style="padding:16px;border-top:1px solid var(--border);">
        <form method="POST" action="{{ route('logout') }}">@csrf
            <button type="submit" class="btn btn-danger btn-sm" style="width:100%;justify-content:center;">⇠ Logout</button>
        </form>
    </div>
</aside>
<div class="main-content">
    <div class="topbar">
        <span class="topbar-title">@yield('title', 'Dashboard')</span>
        <div class="topbar-user">
            <a href="{{ route('admin.profile') }}" style="color:var(--muted); text-decoration:none; font-weight:600; transition:color .2s" onmouseover="this.style.color='var(--accent)'" onmouseout="this.style.color='var(--muted)'">
                👤 {{ auth()->user()->name }}
            </a>
            <span style="background:rgba(37, 99, 235, 0.08); color:var(--accent); padding:6px 12px; border-radius:8px; font-size:14px; font-weight:800; border:1px solid rgba(37, 99, 235, 0.1); text-transform: uppercase; letter-spacing: 0.5px;">{{ strtoupper(auth()->user()->role ?? 'admin') }}</span>
        </div>
    </div>
    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✓ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">✗ {{ session('error') }}</div>
        @endif
        @yield('content')
    </div>
</div>
    <!-- Desktop Notification Logic -->
    <div id="lead-notification-toast" style="position: fixed; bottom: 30px; right: 30px; width: 350px; background: #fff; border-left: 5px solid var(--accent); border-radius: 16px; box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1), 0 10px 10px -5px rgba(0,0,0,0.04); padding: 24px; z-index: 10000; display: none; transform: translateY(20px); transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1); opacity: 0;">
        <div style="display: flex; gap: 16px;">
            <div style="background: rgba(37, 99, 235, 0.1); width: 48px; height: 48px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 24px;">🚨</div>
            <div style="flex: 1;">
                <h4 style="margin: 0; font-size: 16px; font-weight: 800; color: #0f172a;">New Lead Detected!</h4>
                <p id="lead-toast-msg" style="margin: 4px 0 0; font-size: 13px; color: var(--muted); line-height: 1.4;"></p>
                <div style="margin-top: 16px; display: flex; gap: 12px;">
                    <a id="lead-toast-link" href="#" style="font-size: 12px; font-weight: 800; color: var(--accent); text-decoration: none; text-transform: uppercase; letter-spacing: 0.5px;">View Details →</a>
                    <button onclick="hideLeadToast()" style="background: none; border: none; font-size: 12px; font-weight: 700; color: #94a3b8; cursor: pointer; text-transform: uppercase;">Dismiss</button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let lastActivityId = {{ \App\Models\ActivityLog::max('id') ?: 0 }};
        const toast = document.getElementById('lead-notification-toast');
        const toastMsg = document.getElementById('lead-toast-msg');
        const toastLink = document.getElementById('lead-toast-link');

        function showLeadToast(name, phone, leadId) {
            toastMsg.innerText = `${name} just submitted a request. (${phone})`;
            toastLink.href = `/admin/leads/${leadId}`;
            toast.style.display = 'block';
            setTimeout(() => {
                toast.style.opacity = '1';
                toast.style.transform = 'translateY(0)';
            }, 10);
            
            // Audio cue if possible
            try { new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3').play(); } catch(e) {}
            
            // Native Browser Notification
            if (Notification.permission === "granted") {
                new Notification("New Lead: " + name, {
                    body: "Interest captured! Phone: " + phone,
                    icon: "/favicon.ico"
                });
            }
        }

        function hideLeadToast() {
            toast.style.opacity = '0';
            toast.style.transform = 'translateY(20px)';
            setTimeout(() => { toast.style.display = 'none'; }, 300);
        }

        async function checkNewLeads() {
            try {
                const response = await fetch(`/admin/activities/latest?last_id=${lastActivityId}`);
                const data = await response.json();
                
                if (data.count > 0) {
                    data.activities.forEach(activity => {
                        showLeadToast(activity.staff_name.split(' (')[0], '', activity.lead_id);
                    });
                    lastActivityId = data.latest_id;
                }
            } catch (error) {
                console.error("Monitoring failed:", error);
            }
        }

        // Request permission on load
        if (Notification.permission !== "granted" && Notification.permission !== "denied") {
            Notification.requestPermission();
        }

        // Poll every 30 seconds
        setInterval(checkNewLeads, 30000);
    </script>
</body>
</html>
