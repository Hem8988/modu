<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use App\Models\Lead;
use App\Models\ActivityLog;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function index() { return view('admin.customers.index', ['customers' => Customer::latest()->get()]); }

    public function store(Request $request)
    {
        Customer::create($request->all());
        return back()->with('success','Customer added.');
    }

    public function show($id)
    {
        $customer = Customer::with(['invoices','payments'])->findOrFail($id);
        $logs = $customer->lead_id ? ActivityLog::where('lead_id',$customer->lead_id)->latest()->get() : collect();
        return view('admin.customers.show', compact('customer','logs'));
    }

    public function update(Request $request, $id)
    {
        $customer = Customer::findOrFail($id);
        $customer->update($request->only(['name','phone','email','address','project']));
        if ($customer->lead_id) {
            Lead::where('id',$customer->lead_id)->update(['name'=>$customer->name,'phone'=>$customer->phone,'email'=>$customer->email,'address'=>$customer->address,'service'=>$customer->project]);
            ActivityLog::log($customer->lead_id,'Profile Updated','Customer details updated via profile.');
        }
        return back()->with('success','Profile updated.');
    }

    public function destroy($id)
    {
        $customer = Customer::find($id);
        if ($customer?->lead_id) Lead::where('id',$customer->lead_id)->update(['status'=>'new']);
        Customer::destroy($id);
        return redirect()->route('admin.customers.index')->with('success','Customer deleted.');
    }
}
