<?php

namespace App\Http\Controllers;

use App\Models\Combination;
use Illuminate\Http\Request;

class CombinationController extends Controller
{
public function index()
{
    $combinations = Combination::with('subjects')->get();

    if ($combinations->isEmpty()) {
        return response()->json([
            'message' => 'No combinations found.'
        ], 404);
    }

    return response()->json([
        'message' => 'Combinations retrieved successfully.',
        'data' => $combinations
    ]);
}


public function store(Request $request)
{
$validated = $request->validate([
    'name' => 'required|string|unique:combinations,name',
    'shortname' => [
        'required',
        'string',
        'unique:combinations,shortname',
        'regex:/^[A-Z]{3}$/'
    ],
    'subject_ids' => 'required|array|min:1',
    'subject_ids.*' => 'exists:subjects,id'
]);


    $combination = Combination::create([
        'name' => $validated['name'],
        'shortname' => $validated['shortname'],
    ]);

    $combination->subjects()->sync($validated['subject_ids']);

    return response()->json([
        'message' => 'Combination created successfully.',
        'data' => $combination->load('subjects')
    ], 201);
}


public function show($id)
{
    $combination = Combination::with('subjects')->find($id);

    if (! $combination) {
        return response()->json([
            'message' => 'Combination not found.'
        ], 404);
    }

    return response()->json([
        'message' => 'Combination retrieved successfully.',
        'data' => $combination,
    ], 200);
}


public function update(Request $request, $id)
{
    $combination = Combination::find($id);

    if (! $combination) {
        return response()->json(['message' => 'Combination not found.'], 404);
    }

$validated = $request->validate([
    'name' => 'sometimes|required|string|unique:combinations,name,' . $combination->id,
    'shortname' => [
        'sometimes',
        'required',
        'string',
        'unique:combinations,shortname,' . $combination->id,
        'regex:/^[A-Z]{3}$/'
    ],
    'subject_ids' => 'sometimes|required|array|min:1',
    'subject_ids.*' => 'exists:subjects,id',
]);


    // Update fields only if present in request
    if (array_key_exists('name', $validated)) {
        $combination->name = $validated['name'];
    }

    if (array_key_exists('shortname', $validated)) {
        $combination->shortname = $validated['shortname'];
    }

    $combination->save();

    // Sync subjects only if subject_ids provided
    if (array_key_exists('subject_ids', $validated)) {
        $combination->subjects()->sync($validated['subject_ids']);
    }

    return response()->json([
        'message' => 'Combination updated successfully.',
        'data' => $combination->load('subjects'),
    ], 200);
}


public function destroy($id)
{
    $combination = Combination::find($id);

    if (! $combination) {
        return response()->json([
            'message' => 'Combination not found.'
        ], 404);
    }

    try {
        $combination->subjects()->detach();
        $combination->delete();

        return response()->json([
            'message' => 'Combination and its related subjects were successfully removed.'
        ], 200);
    } catch (\Exception $e) {
        return response()->json([
            'message' => 'Failed to delete the combination. Please try again later.',
            'error' => $e->getMessage()
        ], 500);
    }
}

}
