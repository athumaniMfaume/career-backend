<?php

namespace App\Http\Controllers;

use App\Models\JobList;
use App\Models\Program;
use Illuminate\Http\Request;

class JobProgramController extends Controller
{
    // Fetch all jobs with their related programs
    public function index()
    {
        $jobs = JobList::with('programs')
            ->whereHas('programs')
            ->get();

        if ($jobs->isEmpty()) {
            return response()->json(['message' => 'No job listings with programs found'], 404);
        }

        return response()->json($jobs);
    }

    // Assign programs to a job
    public function store(Request $request)
    {
        $validated = $request->validate([
            'job_id' => 'required|exists:jobs_listing,id',
            'program_ids' => 'required|array|min:1',
            'program_ids.*' => 'exists:programs,id',
        ]);

        $job = JobList::findOrFail($validated['job_id']);

        // Attach or sync programs to job
        $job->programs()->sync($validated['program_ids']);

        return response()->json([
            'message' => 'Programs assigned successfully to the job',
            'job' => $job->load('programs'),
        ], 201);
    }

    // Show specific job with its programs
    public function show($id)
    {
        $job = JobList::with('programs')->find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        return response()->json([
            'job' => $job->title,
            'programs' => $job->programs
        ]);
    }

    // Update program list for a specific job
    public function update(Request $request, $id)
    {
        $job = JobList::find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        if ($request->has('program_ids')) {
            $validated = $request->validate([
                'program_ids' => 'array|min:1',
                'program_ids.*' => 'exists:programs,id',
            ]);

            $job->programs()->sync($validated['program_ids']);
        }

        return response()->json([
            'message' => 'Job programs updated successfully.',
            'job' => $job->title,
            'programs' => $job->programs()->get()
        ]);
    }

    // Detach all programs from a specific job
    public function destroy($id)
    {
        $job = JobList::find($id);

        if (!$job) {
            return response()->json(['message' => 'Job not found'], 404);
        }

        $job->programs()->detach();

        return response()->json([
            'message' => 'All programs detached from the job successfully.',
            'job' => $job->title,
        ]);
    }
}
