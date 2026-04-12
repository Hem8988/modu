<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Customer;

class InvoiceController extends Controller
{
    public function index()
    {
        $invoices = Invoice::with('customer')->latest()->get();
        return view('admin.invoices.index', compact('invoices'));
    }

    public function show($id)
    {
        $invoice = Invoice::with(['customer','payments'])->findOrFail($id);
        return view('admin.invoices.show', compact('invoice'));
    }

    public function print($id)
    {
        $invoice = Invoice::with(['customer','payments'])->findOrFail($id);
        return view('admin.invoices.print', compact('invoice'));
    }

    public function store(\Illuminate\Http\Request $request)
    {
        $data = $request->all();
        if (empty($data['invoice_number'])) {
            $data['invoice_number'] = 'INV-' . strtoupper(uniqid());
        }
        
        // Registry Synchronization: amount must mirror total for industrial compliance
        $data['amount'] = $data['total'] ?? 0;
        
        Invoice::create($data);
        return back()->with('success', "✓ Official Invoice #" . $data['invoice_number'] . " surgically issued to the registry.");
    }

    public function updateStatus(\Illuminate\Http\Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $old     = $invoice->status;
        $invoice->update(['status' => $request->status]);
        
        // If status is paid, we ensure 'due' is cleared for industrial accuracy
        if ($invoice->status === 'paid') {
            $invoice->update(['due' => 0]);
        }
        
        return back()->with('success', "✓ Invoice status updated: " . strtoupper($old) . " ➔ " . strtoupper($invoice->status));
    }

    public function update(\Illuminate\Http\Request $request, $id)
    {
        $invoice = Invoice::findOrFail($id);
        $invoice->update($request->only(['total','due','status','due_date']));
        return back()->with('success', "✓ Project Ledger successfully updated.");
    }

    public function destroy($id)
    {
        Invoice::destroy($id);
        return redirect()->route('admin.invoices.index')->with('success', "✓ Invoice removed from the active registry.");
    }
}
