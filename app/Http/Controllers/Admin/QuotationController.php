<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Quote;
use App\Models\QuoteItem;
use App\Models\Lead;
use App\Models\Product;
use App\Models\ActivityLog;
use App\Services\TwilioService;
use Illuminate\Http\Request;

class QuotationController extends Controller
{
    public function index()
    {
        $quotations = Quote::with('lead')->latest()->get();
        return view('admin.quotations.index', compact('quotations'));
    }

    public function builder($leadId)
    {
        $lead          = Lead::findOrFail($leadId);
        $products      = Product::all();
        $existingQuote = Quote::where('lead_id',$leadId)->where('status','draft')->first();
        $items         = $existingQuote ? $existingQuote->items : collect();
        $quoteNumber   = $existingQuote ? $existingQuote->quote_number : Quote::generateNumber();
        return view('admin.quotations.builder', compact('lead','products','existingQuote','items','quoteNumber'));
    }

    public function save(Request $request, $leadId)
    {
        $quoteId = $request->quote_id;
        $data = [
            'lead_id'      => $leadId,
            'quote_number' => $request->quote_number,
            'total_amount' => $request->total_amount,
            'expiry_date'  => now()->addDays(30),
            'status'       => 'draft'
        ];
        
        $quote = $quoteId ? tap(Quote::find($quoteId))->update($data) : Quote::create($data);
        $quote->items()->delete();
        
        if ($request->has('items')) {
            foreach ($request->items as $item) {
                // Combine installation notes with selected attributes
                $options = $item['options'] ?? [];
                if (!empty($item['notes'])) {
                    $options['installation_note'] = $item['notes'];
                }
                
                $quote->items()->create([
                    'product_id'   => $item['product_id'] ?? null,
                    'product_name' => $item['name'] ?? 'Custom Unit',
                    'width'        => $item['width'] ?? 0,
                    'height'       => $item['height'] ?? 0,
                    'quantity'     => $item['quantity'] ?? 1,
                    'unit_price'   => $item['price'] ?? ($item['rate'] ?? 0),
                    'subtotal'     => $item['total'] ?? 0,
                    'options_json' => $options
                ]);
            }
        }
        
        // Finalize Lead Status & Activity
        $lead = Lead::findOrFail($leadId);
        $lead->update([
            'amount' => $request->total_amount,
            'status' => 'quotation_sent'
        ]);
        
        ActivityLog::log($leadId, 'Proposal Generated', "Professional proposal #{$request->quote_number} issued for \${$request->total_amount}.");
        
        return redirect()->route('admin.leads.show', $leadId)->with('success', "✓ Proposal #{$request->quote_number} successfully saved to the project registry.");
    }

    public function show($quoteId)
    {
        $quote = Quote::with(['lead','items'])->findOrFail($quoteId);
        return view('admin.quotations.show', ['quote'=>$quote,'lead'=>$quote->lead,'items'=>$quote->items]);
    }

    public function clientView($token)
    {
        $quote = Quote::where('client_token',$token)->with(['lead','items'])->firstOrFail();
        return view('quote_client', compact('quote'));
    }

    public function clientAccept(Request $request, $token)
    {
        $quote = Quote::where('client_token', $token)->firstOrFail();
        
        $quote->update([
            'status'         => 'accepted',
            'signature_data' => $request->signature_data, // Drawing
            'signature_name' => $request->name,
            'signature_ip'   => $request->ip(),
            'signed_at'      => now(),
        ]);

        // Progress the Lead stage
        $quote->lead?->update(['status' => 'deal_won']);

        ActivityLog::log($quote->lead_id, 'Proposal Signed Online', "Customer ({$request->name}) signed Proposal #{$quote->quote_number} digitally.");

        // WhatsApp Notification to Admin
        $twilio = new TwilioService();
        $adminPhone = config('services.twilio.admin_phone');
        $msg = "🚀 *Deal Won!* Proposal #{$quote->quote_number} has been digitally signed by *{$request->name}*.\n\n" .
                "Project Valuation: \${$quote->total_amount}\n" .
                "View details: " . route('admin.leads.show', $quote->lead_id);
        $twilio->send("whatsapp:".$adminPhone, $msg);

        return redirect()->route('quote.client', $token)->with('success', 'Thank you! The proposal has been successfully signed and accepted.');
    }

    public function convertToInvoice($id)
    {
        $quote = Quote::with('lead')->findOrFail($id);
        $lead  = $quote->lead;
        
        // Ensure customer exists
        $customer = \App\Models\Customer::where('lead_id', $lead->id)->first();
        if (!$customer) {
            $customer = \App\Models\Customer::create([
                'lead_id' => $lead->id,
                'name'    => $lead->name,
                'phone'   => $lead->phone,
                'email'   => $lead->email,
                'address' => $lead->address,
                'project' => $lead->shades_needed,
                'source'  => $lead->source
            ]);
        }

        // Create Invoice
        $invoice = \App\Models\Invoice::create([
            'customer_id'    => $customer->id,
            'invoice_number' => 'INV-' . strtoupper(uniqid()),
            'total'          => $quote->total_amount,
            'amount'         => $quote->total_amount,
            'due'            => $quote->total_amount,
            'status'         => 'unpaid',
            'due_date'       => now()->addDays(7)
        ]);

        $quote->update(['status' => 'accepted']);
        ActivityLog::log($lead->id, 'Quote → Invoice', 'Quote #'.$quote->quote_number.' converted to Invoice #'.$invoice->invoice_number);
        
        return redirect()->route('admin.invoices.show', $invoice->id)->with('success', 'Converted to Invoice successfully!');
    }

    public function updateStatus(Request $request, $id)
    {
        $quote = Quote::findOrFail($id);
        $old   = $quote->status;
        $quote->update(['status' => $request->status]);
        
        $msg = "Proposal Status Change: " . strtoupper($old) . " ➔ " . strtoupper($quote->status);
        ActivityLog::log($quote->lead_id, 'Proposal Updated', $msg);
        
        // Automatic Pipeline Synchronization
        if ($quote->status === 'sent') {
            $quote->lead?->update(['status' => 'quotation_sent']);
        }

        return back()->with('success', "✓ Proposal #" . $quote->quote_number . " updated to " . strtoupper($quote->status) . ".");
    }

    public function destroy($id)
    {
        $quote = Quote::findOrFail($id);
        ActivityLog::log($quote->lead_id, 'Quote Deleted', 'Quote #' . $quote->quote_number . ' removed.');
        $quote->delete();
        return redirect()->route('admin.quotations.index')->with('success', 'Quotation deleted.');
    }
}
