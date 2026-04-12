<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enquiry;
use App\Models\Lead;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class EnquiryController extends Controller
{
    public function index()
    {
        return view('admin.enquiries.index', ['enquiries' => Enquiry::latest()->get()]);
    }

    public function store(Request $request)
    {
        Enquiry::create($request->only(['name','email','phone','city','project','budget','message','source']) + ['status'=>'pending']);
        return back()->with('success','Enquiry added.');
    }

    public function convert($id)
    {
        $enquiry = Enquiry::findOrFail($id);
        $lead = Lead::create([
            'name'        => $enquiry->name,
            'email'       => $enquiry->email,
            'phone'       => $enquiry->phone,
            'city'        => $enquiry->city,
            'budget'      => $enquiry->budget,
            'source'      => $enquiry->source,
            'campaign'    => $enquiry->campaign,
            'shades_needed' => $enquiry->project,
            'feedback'    => $enquiry->message,
            'enquiry_id'  => $id,
            'status'      => 'new_lead'
        ]);
        $lead->lead_score = $lead->calculateScore(); $lead->save();
        $enquiry->update(['status'=>'converted']);
        ActivityLog::log($lead->id,'Converted from Enquiry','Enquiry #'.$id.' converted.');
        return back()->with('success','Enquiry converted to lead.');
    }

    public function spam($id)
    {
        Enquiry::findOrFail($id)->update(['status'=>'spam']);
        return back()->with('success','Marked as spam.');
    }

    public function update(Request $request, $id)
    {
        $enquiry = Enquiry::findOrFail($id);
        $enquiry->update($request->only(['name','email','phone','city','project','budget','message']));
        return back()->with('success','Enquiry updated.');
    }

    public function destroy($id)
    {
        Enquiry::destroy($id);
        return back()->with('success','Enquiry deleted.');
    }
}
