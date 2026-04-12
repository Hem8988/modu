<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Complaint;
use App\Models\Feedback;
use App\Models\ServiceRequest;
use App\Models\Resolution;
use App\Models\Customer;
use App\Models\User;
use Illuminate\Http\Request;

class ComplaintController extends Controller
{
    public function index()
    {
        $complaints = Complaint::with(['customer', 'assignedStaff'])->latest()->paginate(10);
        $feedbacks = Feedback::with('customer')->latest()->paginate(10);
        $serviceRequests = ServiceRequest::with('customer')->latest()->paginate(10);
        $resolutions = Resolution::with(['complaint', 'staff'])->latest()->get();
        
        $staff = User::all();
        $customers = Customer::all();

        $stats = [
            'total_feedback'   => Feedback::count(),
            'open_complaints'  => Complaint::where('status', 'open')->count(),
            'active_resolving' => Complaint::whereIn('status', ['progress', 'in_progress'])->count(),
            'resolved_total'   => Complaint::where('status', 'resolved')->count(),
            'csat_score'       => Feedback::avg('rating') ?? 4.8,
            'avg_resp_time'    => '2.4 Hours'
        ];

        return view('admin.complaints.index', compact(
            'complaints', 'feedbacks', 'serviceRequests', 'resolutions', 'staff', 'customers', 'stats'
        ));
    }

    public function store(Request $request)
    {
        $request->validate(['customer_id' => 'required', 'title' => 'required', 'priority' => 'required']);
        Complaint::create($request->all());
        return back()->with('success', '✓ Complaint logged in the registry.');
    }

    public function storeFeedback(Request $request)
    {
        Feedback::create($request->all());
        return back()->with('success', '✓ Customer feedback pulse recorded.');
    }

    public function storeServiceRequest(Request $request)
    {
        ServiceRequest::create($request->all());
        return back()->with('success', '✓ Service request dispatched to technical registry.');
    }

    public function storeResolution(Request $request)
    {
        Resolution::create($request->all());
        Complaint::find($request->complaint_id)->update(['status' => 'resolved']);
        return back()->with('success', '✓ Resolution forensic pulse established.');
    }

    public function update(Request $request, $id)
    {
        Complaint::findOrFail($id)->update($request->all());
        return back()->with('success', '✓ Registry pulse synchronized.');
    }
}
