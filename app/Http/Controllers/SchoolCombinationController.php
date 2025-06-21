<?php

namespace App\Http\Controllers;

use App\Models\School;
use App\Models\Combination;
use Illuminate\Http\Request;

class SchoolCombinationController extends Controller
{
    /**
     * Display a listing of schools with A-Level or Both education levels and their combinations.
     */
    public function index()
    {
        $schools = School::with('combinations')
            ->whereIn('education_level', ['a_level', 'both'])
            ->get();

        if ($schools->isEmpty()) {
            return response()->json(['message' => 'No schools found for A-Level combinations'], 404);
        }

        return response()->json(['data' => $schools]);
    }

    /**
     * Assign combinations to a school.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'school_id' => 'required|exists:schools,id',
            'combination_ids' => 'required|array|min:1',
            'combination_ids.*' => 'exists:combinations,id',
        ]);

        $school = School::findOrFail($validated['school_id']);

        if (!in_array($school->education_level, ['a_level', 'both'])) {
            return response()->json(['message' => 'This school does not offer A-Level education'], 422);
        }

        $school->combinations()->sync($validated['combination_ids']);

        return response()->json([
            'message' => 'Combinations assigned successfully.',
            'data' => $school->load('combinations'),
        ], 201);
    }

    /**
     * Display the specified school's combinations.
     */
    public function show($id)
    {
        $school = School::with('combinations')
            ->where('id', $id)
            ->whereIn('education_level', ['a_level', 'both'])
            ->first();

        if (!$school) {
            return response()->json(['message' => 'School not found or not eligible for combinations'], 404);
        }

        return response()->json([
            'school' => $school->name,
            'combinations' => $school->combinations,
        ]);
    }

    /**
     * Update combinations for a specific school.
     */
    public function update(Request $request, $id)
    {
        $school = School::where('id', $id)
            ->whereIn('education_level', ['a_level', 'both'])
            ->first();

        if (!$school) {
            return response()->json(['message' => 'School not found or not eligible for combinations'], 404);
        }

        if ($request->has('combination_ids')) {
            $validated = $request->validate([
                'combination_ids' => 'array|min:1',
                'combination_ids.*' => 'exists:combinations,id',
            ]);

            $school->combinations()->sync($validated['combination_ids']);
        }

        return response()->json([
            'message' => 'School combination data updated successfully.',
            'school' => $school->name,
            'combinations' => $school->combinations()->get(),
        ]);
    }

    /**
     * Remove all combinations from a specific school.
     */
    public function clearCombinations($id)
    {
        $school = School::where('id', $id)
            ->whereIn('education_level', ['a_level', 'both'])
            ->first();

        if (!$school) {
            return response()->json(['message' => 'School not found or not eligible for combinations'], 404);
        }

        $school->combinations()->detach();

        return response()->json([
            'message' => 'All combinations detached from the school successfully.',
            'school' => $school->name,
        ]);
    }

    /**
     * Remove a specific combination from a school.
     */
    public function detachCombination($schoolId, $combinationId)
    {
        $school = School::where('id', $schoolId)
            ->whereIn('education_level', ['a_level', 'both'])
            ->first();

        if (!$school) {
            return response()->json(['message' => 'School not found or not eligible for combinations'], 404);
        }

        if (!$school->combinations->contains($combinationId)) {
            return response()->json(['message' => 'Combination not found for this school'], 404);
        }

        $school->combinations()->detach($combinationId);

        return response()->json([
            'message' => 'Combination detached from the school successfully.',
            'school' => $school->name,
        ]);
    }
}
