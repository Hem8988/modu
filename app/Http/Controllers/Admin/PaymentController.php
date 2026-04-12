<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use App\Models\Invoice;
use App\Models\Customer;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments      = Payment::with(['customer','invoice'])->latest()->get();
        $unpaidInvoices = Invoice::with('customer')->where('due','>',0)->get();
        return view('admin.payments.index', compact('payments','unpaidInvoices'));
    }

    public function print($id)
    {
        $payment = Payment::with(['customer','invoice'])->findOrFail($id);
        return view('admin.payments.print', compact('payment'));
    }

    public function store(Request $request)
    {
        $invoice = Invoice::findOrFail($request->invoice_id);
        Payment::create(['customer_id'=>$invoice->customer_id,'invoice_id'=>$invoice->id,'amount'=>$request->amount,'mode'=>$request->mode,'notes'=>$request->notes,'date'=>now()->toDateString()]);
        $newPaid = $invoice->paid + $request->amount;
        $invoice->update(['paid'=>$newPaid,'due'=>$invoice->total - $newPaid,'status'=>($invoice->total - $newPaid <= 0 ? 'paid' : 'partial')]);
        return back()->with('success','Payment recorded.');
    }

    public function update(Request $request, $id)
    {
        $payment = Payment::findOrFail($id);
        $oldAmount = $payment->amount;
        $payment->update($request->only(['amount','mode','notes']));
        
        $invoice = $payment->invoice;
        if ($invoice) {
            $newPaid = ($invoice->paid - $oldAmount) + $request->amount;
            $invoice->update(['paid'=>$newPaid,'due'=>$invoice->total - $newPaid,'status'=>($newPaid<=0?'unpaid':($invoice->total-$newPaid>0?'partial':'paid'))]);
        }
        return back()->with('success','Payment updated.');
    }

    public function destroy($id)
    {
        $p = Payment::findOrFail($id);
        $invoice = $p->invoice;
        if ($invoice) {
            $newPaid = $invoice->paid - $p->amount;
            $invoice->update(['paid'=>$newPaid,'due'=>$invoice->total - $newPaid,'status'=>($newPaid<=0?'unpaid':($invoice->total-$newPaid>0?'partial':'paid'))]);
        }
        $p->delete();
        return back()->with('success','Payment deleted.');
    }
}
