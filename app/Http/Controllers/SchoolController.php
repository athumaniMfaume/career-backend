<?php

namespace App\Http\Controllers;

use App\Models\School;
use Illuminate\Http\Request;

class SchoolController extends Controller
{
public function index(Request $request)
{
    $query = School::with('locations');

    // Check if a search keyword is provided
    if ($request->has('search') && !empty($request->search)) {
        $search = $request->search;
        $query->where('name', 'like', "%{$search}%");
    }

    $schools = $query->paginate(5);

    if ($schools->isEmpty()) {
        return response()->json([
            'message' => 'No schools available at the moment.',
            'data' => []
        ], 200);
    }

    return response()->json($schools, 200);
}



public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|unique:schools,name',
        'location_ids' => 'required|array|min:1',
        'location_ids.*' => 'exists:locations,id',

        'type' => 'nullable|in:college,university|required_without:education_level|exclude_if:education_level,!=,null',
        'education_level' => 'nullable|in:o_level,a_level,both|required_without:type|exclude_if:type,!=,null',
    ]);

    $data = $request->only('name');

    if ($request->filled('type')) {
        $data['type'] = $request->type;
        $data['education_level'] = null;
    } elseif ($request->filled('education_level')) {
        $data['education_level'] = $request->education_level;
        $data['type'] = null;
    }

    // Create the school
    $school = School::create($data);

    // Attach selected location IDs to the pivot table
    $school->locations()->sync($request->location_ids);

    // Load the related locations and return everything
    return response()->json($school->load('locations'), 201);
}




public function show($id)
{
    $school = School::with('locations')->find($id);

    if (!$school) {
        return response()->json([
            'message' => 'School not found.'
        ], 404);
    }

    return response()->json($school);
}

public function update(Request $request, $id)
{
    $school = School::findOrFail($id);

    $validated = $request->validate([
        'name' => 'sometimes|string|unique:schools,name,' . $school->id,
        'location_ids' => 'sometimes|array|min:1',
        'location_ids.*' => 'exists:locations,id',
        'education_level' => 'sometimes|in:o_level,a_level,both',
        'type' => 'sometimes|in:college,university',
    ]);

    if ($request->has('name')) {
        $school->name = $validated['name'];
    }
    if ($request->has('education_level')) {
        $school->education_level = $validated['education_level'];
    }
    if ($request->has('type')) {
        $school->type = $validated['type'];
    }
    $school->save();

    if ($request->has('location_ids')) {
        $school->locations()->sync($validated['location_ids']);
    }

    return response()->json([
        'message' => 'School updated successfully',
        'school' => $school->load('locations'),
    ]);
}



public function destroy($id)
{
    $school = School::find($id);

    if (!$school) {
        return response()->json(['message' => 'School not found'], 404);
    }

    // Detach related locations
    $school->locations()->detach();

    // Delete the school
    $school->delete();

    return response()->json(['message' => 'School deleted successfully'], 200);
}

}

