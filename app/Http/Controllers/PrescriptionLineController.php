<?php

namespace App\Http\Controllers;

use App\Models\PrescriptionLine;
use App\Models\Prescription;
use App\Models\Medication;
use Illuminate\Http\Request;

class PrescriptionLineController extends Controller
{
    /**
     * Create a new controller instance.
     */
    public function __construct()
    {
        // Pas d'authentification
    }

    /**
     * Store a new prescription line.
     */
    public function store(Request $request)
    {
        $request->validate([
            'prescription_id' => 'required|exists:prescriptions,id',
            'medication_id' => 'required|exists:medications,id',
            'dosage' => 'required|string|max:255',
            'frequency' => 'required|string|max:255',
            'duration_days' => 'required|integer|min:1',
            'quantity' => 'nullable|integer|min:1',
            'instructions' => 'nullable|string|max:1000',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after_or_equal:start_date',
            'notes' => 'nullable|string|max:1000',
        ]);

        $prescriptionLine = PrescriptionLine::create([
            'prescription_id' => $request->prescription_id,
            'medication_id' => $request->medication_id,
            'dosage' => $request->dosage,
            'frequency' => $request->frequency,
            'duration_days' => $request->duration_days,
            'quantity' => $request->quantity,
            'instructions' => $request->instructions,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'notes' => $request->notes,
        ]);

        $prescriptionLine->load(['prescription', 'medication']);

        return response()->json([
            'message' => 'Ligne de prescription créée avec succès',
            'prescription_line' => [
                'id' => $prescriptionLine->id,
                'medication_name' => $prescriptionLine->medication->full_name,
                'dosage' => $prescriptionLine->dosage,
                'frequency' => $prescriptionLine->frequency,
                'duration_days' => $prescriptionLine->duration_days,
                'summary' => $prescriptionLine->summary,
                'status' => $prescriptionLine->status,
            ]
        ], 201);
    }

    /**
     * Get prescription lines for a specific prescription.
     */
    public function getByPrescription($prescriptionId)
    {
        $lines = PrescriptionLine::where('prescription_id', $prescriptionId)
            ->with(['medication', 'prescription'])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json([
            'prescription_lines' => $lines->map(function ($line) {
                return [
                    'id' => $line->id,
                    'medication' => [
                        'id' => $line->medication->id,
                        'name' => $line->medication->full_name,
                        'display_name' => $line->medication->display_name,
                    ],
                    'dosage' => $line->dosage,
                    'frequency' => $line->frequency,
                    'duration_days' => $line->duration_days,
                    'quantity' => $line->quantity,
                    'instructions' => $line->instructions,
                    'start_date' => $line->start_date->format('Y-m-d'),
                    'end_date' => $line->end_date->format('Y-m-d'),
                    'remaining_days' => $line->remaining_days,
                    'status' => $line->status,
                    'summary' => $line->summary,
                ];
            })
        ]);
    }

    /**
     * Update a prescription line.
     */
    public function update(Request $request, PrescriptionLine $prescriptionLine)
    {
        $request->validate([
            'dosage' => 'sometimes|string|max:255',
            'frequency' => 'sometimes|string|max:255',
            'duration_days' => 'sometimes|integer|min:1',
            'quantity' => 'sometimes|integer|min:1',
            'instructions' => 'sometimes|string|max:1000',
            'start_date' => 'sometimes|date',
            'end_date' => 'sometimes|date|after_or_equal:start_date',
            'notes' => 'sometimes|string|max:1000',
        ]);

        $prescriptionLine->update($request->only([
            'dosage', 'frequency', 'duration_days', 'quantity', 
            'instructions', 'start_date', 'end_date', 'notes'
        ]));

        return response()->json([
            'message' => 'Ligne de prescription mise à jour avec succès',
            'prescription_line' => [
                'id' => $prescriptionLine->id,
                'summary' => $prescriptionLine->summary,
                'status' => $prescriptionLine->status,
            ]
        ]);
    }

    /**
     * Delete a prescription line.
     */
    public function destroy(PrescriptionLine $prescriptionLine)
    {
        $prescriptionLine->delete();

        return response()->json([
            'message' => 'Ligne de prescription supprimée avec succès'
        ]);
    }

    /**
     * Get active prescription lines for a patient.
     */
    public function getActiveByPatient($patientId)
    {
        $lines = PrescriptionLine::whereHas('prescription', function ($query) use ($patientId) {
                $query->where('patient_id', $patientId);
            })
            ->where('end_date', '>=', now())
            ->with(['medication', 'prescription.doctor.user'])
            ->orderBy('end_date', 'asc')
            ->get();

        return response()->json([
            'active_prescription_lines' => $lines->map(function ($line) {
                return [
                    'id' => $line->id,
                    'medication_name' => $line->medication->full_name,
                    'dosage' => $line->dosage,
                    'frequency' => $line->frequency,
                    'instructions' => $line->instructions,
                    'remaining_days' => $line->remaining_days,
                    'end_date' => $line->end_date->format('d/m/Y'),
                    'doctor_name' => $line->prescription->doctor->user->full_name,
                    'summary' => $line->summary,
                ];
            })
        ]);
    }
}
