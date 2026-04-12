<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Lead;
use Illuminate\Http\Request;

class LeadController extends Controller
{
    /**
     * Create a new lead from form submission
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'full_name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|string|max:20',
            'postal_code' => 'required|string|max:10',
            'blinds_type' => 'nullable|string',
            'num_windows' => 'nullable|string',
            'project_timeline' => 'nullable|string',
            'special_message' => 'nullable|string',
        ]);

        $lead = Lead::create([
            'name' => $validated['full_name'],
            'email' => $validated['email'],
            'phone' => $validated['phone'],
            'zip_code' => $validated['postal_code'],
            'shades_needed' => $validated['blinds_type'] ?? null,
            'windows_count' => $validated['num_windows'] ?? null,
            'timeline' => $validated['project_timeline'] ?? null,
            'feedback' => $validated['special_message'] ?? null,
            'status' => 'new',
            'lead_score' => 50,
            'source' => 'website_form',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Lead created successfully!',
            'data' => $lead,
        ], 201);
    }

    /**
     * Get all leads
     */
    public function index()
    {
        $leads = Lead::orderBy('created_at', 'desc')->get();

        return response()->json([
            'success' => true,
            'data' => $leads,
        ], 200);
    }

    /**
     * Get a specific lead
     */
    public function show($id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $lead,
        ], 200);
    }

    /**
     * Update lead status and notes
     */
    public function update(Request $request, $id)
    {
        $lead = Lead::find($id);

        if (!$lead) {
            return response()->json([
                'success' => false,
                'message' => 'Lead not found.',
            ], 404);
        }

        $validated = $request->validate([
            'status' => 'nullable|in:new,contacted,qualified,proposal_sent,won,lost',
            'notes' => 'nullable|string',
            'last_contact_date' => 'nullable|date',
        ]);

        $lead->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Lead updated successfully!',
            'data' => $lead,
        ], 200);
    }
}
