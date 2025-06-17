<?php

namespace App\Http\Controllers;

use App\Models\SymptomCheck;
use App\Models\User;
use Illuminate\Http\Request;

class SymptomCheckController extends Controller
{
    /**
     * Display a listing of symptom checks.
     */
    public function index()
    {
        $symptomChecks = SymptomCheck::with('user')
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('symptom-checks.index', compact('symptomChecks'));
    }

    /**
     * Show the form for creating a new symptom check.
     */
    public function create()
    {
        return view('symptom-checks.create');
    }

    /**
     * Store a newly created symptom check.
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'symptom_text' => 'required|string|min:10',
            'result' => 'nullable|string',
            'recommended_doctor' => 'nullable|string|max:255',
            'urgency_level' => 'nullable|integer|between:1,10',
            'severity' => 'nullable|integer|between:1,10',
            'detected_categories' => 'nullable|array',
            'analysis' => 'nullable|array',
        ]);

        $symptomCheck = SymptomCheck::create($request->all());

        return redirect()->route('symptom-checks.show', $symptomCheck)
            ->with('success', 'Symptom check created successfully.');
    }

    /**
     * Display the specified symptom check.
     */
    public function show(SymptomCheck $symptomCheck)
    {
        $symptomCheck->load('user');
        return view('symptom-checks.show', compact('symptomCheck'));
    }

    /**
     * Show the form for editing the specified symptom check.
     */
    public function edit(SymptomCheck $symptomCheck)
    {
        return view('symptom-checks.edit', compact('symptomCheck'));
    }

    /**
     * Update the specified symptom check.
     */
    public function update(Request $request, SymptomCheck $symptomCheck)
    {
        $request->validate([
            'symptom_text' => 'required|string|min:10',
            'result' => 'nullable|string',
            'recommended_doctor' => 'nullable|string|max:255',
            'urgency_level' => 'nullable|integer|between:1,10',
            'severity' => 'nullable|integer|between:1,10',
            'detected_categories' => 'nullable|array',
            'analysis' => 'nullable|array',
        ]);

        $symptomCheck->update($request->all());

        return redirect()->route('symptom-checks.show', $symptomCheck)
            ->with('success', 'Symptom check updated successfully.');
    }

    /**
     * Remove the specified symptom check.
     */
    public function destroy(SymptomCheck $symptomCheck)
    {
        $symptomCheck->delete();

        return redirect()->route('symptom-checks.index')
            ->with('success', 'Symptom check deleted successfully.');
    }

    /**
     * Get symptom checks by urgency level.
     */
    public function byUrgency($level)
    {
        $symptomChecks = SymptomCheck::byUrgencyLevel($level)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'symptom_checks' => $symptomChecks,
            'count' => $symptomChecks->count()
        ]);
    }

    /**
     * Get high urgency symptom checks.
     */
    public function highUrgency()
    {
        $symptomChecks = SymptomCheck::highUrgency()
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'symptom_checks' => $symptomChecks,
            'count' => $symptomChecks->count()
        ]);
    }

    /**
     * Get symptom checks by category.
     */
    public function byCategory($category)
    {
        $symptomChecks = SymptomCheck::byCategory($category)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'symptom_checks' => $symptomChecks,
            'count' => $symptomChecks->count()
        ]);
    }

    /**
     * Get recent symptom checks.
     */
    public function recent($days = 30)
    {
        $symptomChecks = SymptomCheck::recent($days)
            ->with('user')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'symptom_checks' => $symptomChecks,
            'count' => $symptomChecks->count(),
            'days' => $days
        ]);
    }

    /**
     * Test the model functionality.
     */
    public function test()
    {
        // Get the first symptom check
        $symptomCheck = SymptomCheck::with('user')->first();

        if (!$symptomCheck) {
            return response()->json([
                'error' => 'No symptom checks found'
            ]);
        }

        return response()->json([
            'symptom_check' => $symptomCheck,
            'urgency_text' => $symptomCheck->urgency_level_text,
            'severity_text' => $symptomCheck->severity_text,
            'urgency_color' => $symptomCheck->urgency_color,
            'severity_color' => $symptomCheck->severity_color,
            'categories_summary' => $symptomCheck->categories_summary,
            'primary_category' => $symptomCheck->primary_category,
            'short_symptom_text' => $symptomCheck->short_symptom_text,
            'short_result' => $symptomCheck->short_result,
            'formatted_date' => $symptomCheck->formatted_date,
            'requires_immediate_attention' => $symptomCheck->requiresImmediateAttention(),
            'user' => $symptomCheck->user
        ]);
    }
}
