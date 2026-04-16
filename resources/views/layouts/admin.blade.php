<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $globalSettings['site_name'] ?? 'ModuShade CRM' }} | @yield('title', 'Admin')</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background-color: #f8f9fa; font-family: 'Inter', 'Segoe UI', sans-serif; }
        /* Sidebar Styles */
        .sidebar { min-height: 100vh; width: 280px; background: #fff; border-right: 1px solid #dee2e6; transition: transform 0.3s ease; position: fixed; top: 0; bottom: 0; left: 0; overflow-y: auto; z-index: 1040; flex-direction: column; }
        .sidebar-logo { font-weight: 900; font-size: 1.25rem; text-decoration: none; padding: 1.25rem 1rem; border-bottom: 1px solid #f1f5f9; display: block; color: #0d6efd;}
        .sidebar-logo span { color: #b89b5e; }
        .nav-link { font-weight: 500; color: #495057; font-size: 0.85rem; padding: 0.6rem 1rem; margin: 0.15rem 0.75rem; border-radius: 0.375rem; display: flex; align-items: center; gap: 0.5rem; transition: all 0.2s; }
        .nav-link:hover, .nav-link.active { background-color: rgba(13, 110, 253, 0.08); color: #0d6efd; font-weight: 600; }
        .nav-section { font-size: 0.7rem; font-weight: 700; text-transform: uppercase; color: #adb5bd; margin: 1.5rem 1rem 0.5rem; letter-spacing: 0.5px; }
        .nav-badge { margin-left: auto; opacity: 0.8; font-size: 0.75rem; font-weight: 600; }
        
        #main-content { min-height: 100vh; display: flex; flex-direction: column; transition: margin-left 0.3s; margin-left: 280px; width: calc(100% - 280px); }
        .topbar { background: #fff; border-bottom: 1px solid #dee2e6; z-index: 1020; position: sticky; top: 0; height: 60px; padding: 0 1.5rem; }
        
        /* Responsive adjustments */
        @media (max-width: 991.98px) {
            .sidebar { transform: translateX(-100%); box-shadow: 0.5rem 0 1rem rgba(0,0,0,0.15); }
            .sidebar.show { transform: translateX(0); }
            #main-content { margin-left: 0; width: 100%; }
            .sidebar-overlay { position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 1030; opacity: 0; visibility: hidden; transition: all 0.3s; }
            .sidebar-overlay.show { opacity: 1; visibility: visible; }
        }

        /* Polyfills for old CSS Grid components */
        .grid-2 { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 1rem; }
        .grid-3 { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1rem; }
        .grid-4 { display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap: 1rem; }
        .grid-5 { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; }
        .grid-auto { display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 1rem; }
        
        /* Badges Polyfill */
        .badge-new { background-color: #cfe2ff; color: #084298; }
        .badge-contacted { background-color: #fff3cd; color: #664d03; }
        .badge-deal_won, .badge-won { background-color: #d1e7dd; color: #0f5132; }
        .badge-lost { background-color: #f8d7da; color: #842029; }
        .badge-appointment { background-color: #e0cffc; color: #511281; }

        /* Legacy Component Polyfills to maintain styling without deep refactor in old pages */
        .card { box-shadow: 0 2px 6px rgba(0,0,0,0.04); border: 1px solid rgba(0,0,0,0.05); border-radius: 0.75rem; margin-bottom: 1rem; bg-white; }
        .card-title { font-size: 1.1rem; font-weight: 700; margin-bottom: 1rem; border-bottom: 2px solid #f1f5f9; padding-bottom: 0.5rem; color: #1e293b;}
        .table-responsive { background: #fff; border-radius: 0.75rem; border: 1px solid rgba(0,0,0,0.05); }
        .table-responsive table { width: 100%; margin-bottom: 0; }
        .table-responsive th { background-color: #f8fafc; font-size: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px; padding: 1rem; border-bottom: 1px solid #e2e8f0; color: #64748b;}
        .table-responsive td { padding: 1rem; border-bottom: 1px solid #f1f5f9; font-size: 0.875rem; vertical-align: middle; }
    </style>
</head>
<body>

<!-- Mobile Sidebar Overlay -->
<div class="sidebar-overlay" id="sidebarOverlay"></div>

<!-- Sidebar -->
<aside class="sidebar d-flex" id="sidebar">
    <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
        <span>MODU</span>SHADE <span class="text-secondary opacity-75 fs-6 ms-1 fw-normal">CRM</span>
    </a>
    
    <div class="flex-grow-1 overflow-y-auto w-100 pb-4">
        <a href="{{ route('admin.dashboard') }}" class="nav-link mt-2 {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
            <i class="fas fa-home fa-fw text-primary"></i> Dashboard Explorer
        </a>

        <!-- SALES HUB -->
        <div class="nav-section mt-3">Sales Operations</div>
        <a href="{{ route('admin.enquiries.index') }}" class="nav-link {{ request()->routeIs('admin.enquiries*') ? 'active' : '' }}">
            <i class="fas fa-inbox fa-fw text-secondary"></i> Enquiries <span class="nav-badge">{{ $sidebarCounts['Enquiries'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index') }}" class="nav-link {{ request()->routeIs('admin.leads*') && !request('status') ? 'active' : '' }}">
            <i class="fas fa-bullseye fa-fw text-secondary"></i> Leads Registry
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'new_lead']) }}" class="nav-link {{ request('status') == 'new_lead' ? 'active' : '' }}">
            <i class="fas fa-circle fa-fw text-primary" style="font-size: 8px;"></i> New Leads <span class="nav-badge">{{ $sidebarCounts['New Leads'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'contacted']) }}" class="nav-link {{ request('status') == 'contacted' ? 'active' : '' }}">
            <i class="fas fa-phone fa-fw text-warning" style="font-size: 10px;"></i> Contacted <span class="nav-badge">{{ $sidebarCounts['Contacted'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'site_visit_scheduled']) }}" class="nav-link {{ request('status') == 'site_visit_scheduled' ? 'active' : '' }}">
            <i class="fas fa-calendar fa-fw text-info" style="font-size: 10px;"></i> Appointment <span class="nav-badge">{{ $sidebarCounts['Appt Scheduled'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'quotation_sent']) }}" class="nav-link {{ request('status') == 'quotation_sent' ? 'active' : '' }}">
            <i class="fas fa-file-invoice fa-fw text-success" style="font-size: 10px;"></i> Quotation Sent <span class="nav-badge">{{ $sidebarCounts['Quotation Sent'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'invoice_sent']) }}" class="nav-link {{ request('status') == 'invoice_sent' ? 'active' : '' }}">
            <i class="fas fa-receipt fa-fw text-dark" style="font-size: 10px;"></i> Invoice Sent <span class="nav-badge">{{ $sidebarCounts['Invoice Sent'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'negotiation']) }}" class="nav-link {{ request('status') == 'negotiation' ? 'active' : '' }}">
            <i class="fas fa-handshake fa-fw text-secondary" style="font-size: 10px;"></i> Negotiation <span class="nav-badge">{{ $sidebarCounts['Negotiation'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'deal_won']) }}" class="nav-link {{ request('status') == 'deal_won' ? 'active' : '' }}">
            <i class="fas fa-trophy fa-fw text-success" style="font-size: 10px;"></i> Deal Won <span class="nav-badge">{{ $sidebarCounts['Won'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.index', ['status' => 'lost']) }}" class="nav-link {{ request('status') == 'lost' ? 'active' : '' }}">
            <i class="fas fa-times fa-fw text-danger" style="font-size: 10px;"></i> Lost <span class="nav-badge">{{ $sidebarCounts['Lost'] ?? 0 }}</span>
        </a>
        <a href="{{ route('admin.leads.pipeline') }}" class="nav-link {{ request()->routeIs('admin.leads.pipeline') ? 'active' : '' }}">
            <i class="fas fa-stream fa-fw text-secondary"></i> Sales Pipeline
        </a>

        <!-- OPERATIONS REGISTRY -->
        <div class="nav-section">Execution & Ops</div>
        <a href="{{ route('admin.appointments.index') }}" class="nav-link {{ request()->routeIs('admin.appointments*') ? 'active' : '' }}">
            <i class="fas fa-building fa-fw text-secondary"></i> Field Appointments
        </a>
        <a href="{{ route('admin.customers.index') }}" class="nav-link {{ request()->routeIs('admin.customers*') ? 'active' : '' }}">
            <i class="fas fa-users fa-fw text-secondary"></i> Customers
        </a>
        <a href="{{ route('admin.installations.index') }}" class="nav-link {{ request()->routeIs('admin.installations*') ? 'active' : '' }}">
            <i class="fas fa-tools fa-fw text-secondary"></i> Installations
        </a>
        <a href="{{ route('admin.complaints.index') }}" class="nav-link {{ request()->routeIs('admin.complaints*') ? 'active' : '' }}">
            <i class="fas fa-exclamation-triangle fa-fw text-secondary"></i> Feedback Hub
        </a>

        <!-- FINANCE COMMAND -->
        <div class="nav-section">Finance & Bidding</div>
        <a href="{{ route('admin.quotations.index') }}" class="nav-link {{ request()->routeIs('admin.quotations*') ? 'active' : '' }}">
            <i class="fas fa-file-contract fa-fw text-secondary"></i> Project Proposals
        </a>
        <a href="{{ route('admin.products.index') }}" class="nav-link {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
            <i class="fas fa-box fa-fw text-secondary"></i> Product Registry
        </a>
        <a href="{{ route('admin.invoices.index') }}" class="nav-link {{ request()->routeIs('admin.invoices*') ? 'active' : '' }}">
            <i class="fas fa-file-invoice-dollar fa-fw text-secondary"></i> Invoices
        </a>
        <a href="{{ route('admin.payments.index') }}" class="nav-link {{ request()->routeIs('admin.payments*') ? 'active' : '' }}">
            <i class="fas fa-money-bill-wave fa-fw text-secondary"></i> Payments Pulse
        </a>

        <!-- REPORTS & ANALYTICS -->
        <div class="nav-section">Intelligence Hub</div>
        <a href="#" class="nav-link">
            <i class="fas fa-chart-line fa-fw text-secondary"></i> Performance Reports
        </a>

        <!-- SETTINGS -->
        <div class="nav-section">System Config</div>
        <a href="{{ route('admin.settings.index') }}" class="nav-link {{ request()->routeIs('admin.settings.*') ? 'active' : '' }}">
            <i class="fas fa-cog fa-fw text-secondary"></i> Global Settings
        </a>
        
       

    </div>
</aside>

<!-- Main Area -->
<div id="main-content">
    <header class="topbar d-flex justify-content-between align-items-center">
        <div class="d-flex align-items-center gap-3">
            <button id="mobileMenuBtn" class="btn btn-light d-lg-none border"><i class="fas fa-bars"></i></button>
            <h5 class="mb-0 fw-bold text-dark fs-5 text-truncate" style="max-width: 200px;">@yield('title', 'Dashboard')</h5>
        </div>
        <div class="d-flex align-items-center gap-2 gap-md-3">
            <a href="{{ route('admin.profile') }}" class="text-decoration-none text-secondary fw-semibold text-nowrap">
                <i class="fas fa-user-circle me-1"></i> {{ auth()->user()->name }}
            </a>
            <form method="POST" action="{{ route('logout') }}" class="m-0">
                @csrf
                <button type="submit" class="btn btn-light btn-sm fw-bold border text-danger" title="Logout"><i class="fas fa-sign-out-alt"></i></button>
            </form>
        </div>
    </header>

    <main class="flex-grow-1 p-3 p-md-4">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show fw-semibold border-0 shadow-sm" role="alert">
                <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show fw-semibold border-0 shadow-sm" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </main>
</div>

<!-- Desktop Notification Logic -->
<div id="lead-notification-toast" class="toast align-items-center text-bg-light border-0 shadow-lg position-fixed bottom-0 end-0 m-4" role="alert" aria-live="assertive" aria-atomic="true" style="z-index: 9999; border-left: 4px solid #0d6efd !important;">
    <div class="d-flex w-100 p-2">
        <div class="toast-body d-flex gap-3 align-items-start w-100">
            <div class="bg-primary bg-opacity-10 rounded p-2 text-primary fs-4"><i class="fas fa-bell"></i></div>
            <div class="flex-grow-1">
                <h6 class="fw-bold mb-1">New Lead Detected!</h6>
                <p id="lead-toast-msg" class="text-secondary small mb-2"></p>
                <div class="d-flex gap-2">
                    <a id="lead-toast-link" href="#" class="btn btn-sm btn-primary fw-bold text-uppercase" style="font-size:0.7rem">View Details</a>
                    <button type="button" class="btn btn-sm btn-light fw-bold text-uppercase" style="font-size:0.7rem" data-bs-dismiss="toast">Dismiss</button>
                </div>
            </div>
        </div>
        <button type="button" class="btn-close me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // Mobile Sidebar Toggle
    const mobileMenuBtn = document.getElementById('mobileMenuBtn');
    const sidebar = document.getElementById('sidebar');
    const sidebarOverlay = document.getElementById('sidebarOverlay');

    if(mobileMenuBtn) {
        mobileMenuBtn.addEventListener('click', () => {
            sidebar.classList.add('show');
            sidebarOverlay.classList.add('show');
        });
    }
    if(sidebarOverlay) {
        sidebarOverlay.addEventListener('click', () => {
            sidebar.classList.remove('show');
            sidebarOverlay.classList.remove('show');
        });
    }

    // Lead Notification logic
    let lastActivityId = {{ \App\Models\ActivityLog::max('id') ?: 0 }};
    const toastElList = [].slice.call(document.querySelectorAll('.toast'));
    const toastList = toastElList.map(function (toastEl) {
        return new bootstrap.Toast(toastEl, {autohide: false})
    });
    
    function showLeadToast(name, phone, leadId) {
        document.getElementById('lead-toast-msg').innerText = `${name} just submitted a request. (${phone})`;
        document.getElementById('lead-toast-link').href = `/admin/leads/${leadId}`;
        const toast = document.getElementById('lead-notification-toast');
        const bsToast = bootstrap.Toast.getOrCreateInstance(toast);
        bsToast.show();
        
        try { new Audio('https://assets.mixkit.co/active_storage/sfx/2869/2869-preview.mp3').play(); } catch(e) {}
        if (Notification.permission === "granted") {
            new Notification("New Lead: " + name, { body: "Interest captured! Phone: " + phone, icon: "/favicon.ico" });
        }
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
        } catch (error) {}
    }

    if (Notification.permission !== "granted" && Notification.permission !== "denied") {
        Notification.requestPermission();
    }
    setInterval(checkNewLeads, 30000);
</script>
@stack('scripts')
</body>
</html>
