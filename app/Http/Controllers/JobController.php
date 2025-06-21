<?php

namespace App\Http\Controllers;

use App\Models\JobList;
use Illuminate\Http\Request;

use function PHPUnit\Framework\isEmpty;

class jobController extends Controller
{
 public function index()
{
    $jobs = JobList::all();

    if ($jobs->isEmpty()) {
        return response()->json([
            'message' => 'No jobs found.'
        ], 404);
    }

    return response()->json([
        'jobs' => $jobs
    ], 200);
}

public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|unique:jobs_listing,title',
    ], [
        'title.unique' => 'This job already exists.',
    ]);

    $job = JobList::create(['title' => $request->title]);

    return response()->json([
        'status' => 'success',
        'data' => $job
    ], 201);
}


 public function show($id)
{
    $job = JobList::find($id);

    if (!$job) {
        return response()->json([
            'message' => 'job not found.'
        ], 404);
    }

    return response()->json($job);
}


public function update(Request $request, $id)
{
    $job = JobList::find($id);

    if (! $job) {
        return response()->json([
            'message' => 'job not found.'
        ], 404);
    }

    $request->validate([
        'title' => 'sometimes|required|string|unique:jobs_listing,title,' . $job->id,
    ]);

    $job->update($request->only('title'));

    return response()->json([
        'message' => 'job updated successfully.',
        'job' => $job,
    ]);
}



public function destroy($id)
{
    $job = JobList::find($id);

    if (! $job) {
        return response()->json([
            'message' => 'job not found.'
        ], 404);
    }

    $job->delete();

        return response()->json([
        'message' => 'job deleted successfully.',
        'job' => $job,
    ]);
}

}

