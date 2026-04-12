<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Lead;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index(Request $request)
    {
        $query = Appointment::with('lead')->orderBy('date');
        if ($s = $request->status) $query->where('status', $s);
        if ($d = $request->date)   $query->where('date', $d);
        if ($q = $request->search) $query->whereHas('lead', fn($b) => $b->where('name','like',"%$q%")->orWhere('phone','like',"%$q%"));
        return view('admin.appointments.index', ['appointments' => $query->get()]);
    }

    public function store(Request $request)
    {
        $app = Appointment::create($request->all());
        \App\Models\ActivityLog::log($request->lead_id, 'Appt Scheduled', ucfirst($app->type).' on '.$app->date->format('M d'));
        return back()->with('success','Appointment scheduled.');
    }

    public function complete($id)
    {
        $app = Appointment::findOrFail($id);
        $app->update(['status' => 'completed']);
        \App\Models\ActivityLog::log($app->lead_id, 'Appt Completed', $app->type.' finished.');
        return back()->with('success','Appointment marked complete.');
    }

    public function update(Request $request, $id)
    {
        $app = Appointment::findOrFail($id);
        $app->update($request->only(['type','date','time','notes','status']));
        \App\Models\ActivityLog::log($app->lead_id, 'Appt Updated', 'Scheduled details changed for '.ucfirst($app->type));
        return back()->with('success','Appointment updated.');
    }

    public function destroy($id)
    {
        $app = Appointment::findOrFail($id);
        \App\Models\ActivityLog::log($app->lead_id, 'Appt Cancelled', $app->type.' removed.');
        $app->delete();
        return back()->with('success','Appointment deleted.');
    }
}
