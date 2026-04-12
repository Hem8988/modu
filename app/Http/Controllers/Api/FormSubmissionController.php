<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\FormSubmission;
use App\Models\Lead;
use Illuminate\Http\Request;

class FormSubmissionController extends Controller
{
    /**
     * Store a newly created form submission in storage and create a lead.
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
        ], [
            'full_name.required' => 'Full name is required',
            'email.required' => 'Email address is required',
            'email.email' => 'Please provide a valid email address',
            'phone.required' => 'Phone number is required',
            'postal_code.required' => 'Postal code is required',
        ]);

        // Save form submission
        $submission = FormSubmission::create($validated);

        // Create lead from form submission
        $lead = Lead::create([
            'form_submission_id' => $submission->id,
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
            'message' => 'Form submitted successfully and lead created!',
            'data' => [
                'form_submission' => $submission,
                'lead' => $lead,
            ],
        ], 201);
    }

    /**
     * Get all form submissions.
     */
    public function index()
    {
        $submissions = FormSubmission::all();

        return response()->json([
            'success' => true,
            'data' => $submissions,
        ], 200);
    }

    /**
     * Get a specific form submission.
     */
    public function show($id)
    {
        $submission = FormSubmission::find($id);

        if (!$submission) {
            return response()->json([
                'success' => false,
                'message' => 'Form submission not found.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => $submission,
        ], 200);
    }
}
