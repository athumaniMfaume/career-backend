<?php

namespace App\Http\Controllers;

use App\Models\Program;
use App\Models\School;
use Illuminate\Http\Request;

class ProgramSchoolController extends Controller
{
    /**
     * Get all schools that have attached programs.
     */
    public function index()
    {
        $schools = School::whereHas('programs')
            ->with(['programs:id,name']) // Removed shortname here
            ->get();

        if ($schools->isEmpty()) {
            return response()->json([
                'status' => 'not_found',
                'message' => 'No schools with related programs found.',
                'data' => [],
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $schools,
        ]);
    }

    /**
     * Attach programs to a school (without removing existing ones).
     */
    public function store(Request $request)
    {
        $request->validate([
            'school_id' => 'required|exists:schools,id',
            'program_ids' => 'required|array|distinct',
            'program_ids.*' => 'exists:programs,id',
        ]);

        $school = $this->getEligibleSchool($request->school_id);
        if (!$school) {
            return $this->ineligibleResponse();
        }

        $school->programs()->syncWithoutDetaching($request->program_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Programs attached to school successfully.',
            'data' => $school->load('programs:id,name'), // Removed shortname here
        ]);
    }

    /**
     * Show one school with its programs.
     */
    public function show($id)
    {
        $school = School::whereHas('programs')
            ->with(['programs:id,name']) // Removed shortname here
            ->find($id);

        if (!$school) {
            return response()->json([
                'status' => 'error',
                'message' => 'School not found or has no programs.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $school,
        ]);
    }

    /**
     * Update program assignments to a school (sync).
     */
    public function update(Request $request, $id)
    {
        $school = $this->getEligibleSchool($id);
        if (!$school) {
            return $this->ineligibleResponse();
        }

        $validated = $request->validate([
            'program_ids' => 'nullable|array|distinct',
            'program_ids.*' => 'exists:programs,id',
        ]);

        if ($request->has('program_ids')) {
            if (!empty($validated['program_ids'])) {
                $school->programs()->sync($validated['program_ids']);
                $message = 'Programs updated for school successfully.';
            } else {
                $school->programs()->detach();
                $message = 'All programs detached from the school.';
            }
        } else {
            $message = 'No program changes made.';
        }

        return response()->json([
            'status' => 'success',
            'message' => $message,
            'data' => $school->load('programs:id,name'), // Removed shortname here
        ]);
    }

    /**
     * Delete a school and detach its programs.
     */
    public function destroy($id)
    {
        $school = $this->getEligibleSchool($id);
        if (!$school) {
            return response()->json([
                'status' => 'error',
                'message' => 'School not found.',
            ], 404);
        }

        if ($school->programs()->exists()) {
            $school->programs()->detach();
        }

        $school->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'School deleted successfully.',
        ]);
    }

    /**
     * Attach more programs to a school without removing existing ones.
     */
    public function attachPrograms(Request $request, $schoolId)
    {
        $request->validate([
            'program_ids' => 'required|array|distinct',
            'program_ids.*' => 'exists:programs,id',
        ]);

        $school = $this->getEligibleSchool($schoolId);
        if (!$school) {
            return $this->ineligibleResponse();
        }

        $school->programs()->syncWithoutDetaching($request->program_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Programs attached.',
        ]);
    }

    /**
     * Detach a single program from a school.
     */
    public function detachProgram($schoolId, $programId)
    {
        $school = $this->getEligibleSchool($schoolId);
        if (!$school) {
            return $this->ineligibleResponse();
        }

        $school->programs()->detach($programId);

        return response()->json([
            'status' => 'success',
            'message' => 'Program detached.',
        ]);
    }

    /**
     * Detach all programs from a school.
     */
    public function detachAllPrograms($schoolId)
    {
        $school = $this->getEligibleSchool($schoolId);
        if (!$school) {
            return $this->ineligibleResponse();
        }

        $school->programs()->detach();

        return response()->json([
            'status' => 'success',
            'message' => 'All programs detached.',
        ]);
    }

    /**
     * Helper: Get a school only if eligible.
     */
    private function getEligibleSchool($id)
    {
        return School::where('id', $id)
            ->whereIn('type', ['college', 'university'])
            ->first();
    }

    /**
     * Helper: Ineligible school error response.
     */
    private function ineligibleResponse()
    {
        return response()->json([
            'status' => 'error',
            'message' => "Programs can only be managed for schools of type 'college' or 'university'.",
        ], 422);
    }
}
