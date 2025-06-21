<?php

namespace App\Http\Controllers;

namespace App\Http\Controllers;

use App\Models\Combination;
use Illuminate\Http\Request;

class CombinationProgramController extends Controller
{
    public function index()
    {
        $combinations = Combination::with('programs')->get();

        return response()->json([
            'status' => 'success',
            'data' => $combinations,
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'combination_id' => 'required|exists:combinations,id',
            'program_ids' => 'required|array|min:1',
            'program_ids.*' => 'exists:programs,id',
        ]);

        $combination = Combination::findOrFail($request->combination_id);

        // Attach new programs without detaching existing ones
        $combination->programs()->syncWithoutDetaching($request->program_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Programs attached to combination successfully',
            'data' => $combination->load('programs'),
        ]);
    }

    public function show($id)
    {
        $combination = Combination::with('programs')->find($id);

        if (!$combination) {
            return response()->json(['status' => 'error', 'message' => 'Combination not found'], 404);
        }

        return response()->json(['status' => 'success', 'data' => $combination]);
    }

    public function update(Request $request, $id)
    {
        $combination = Combination::find($id);

        if (!$combination) {
            return response()->json(['status' => 'error', 'message' => 'Combination not found'], 404);
        }

        $request->validate([
            'program_ids' => 'required|array|min:1',
            'program_ids.*' => 'exists:programs,id',
        ]);

        $combination->programs()->sync($request->program_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Combination programs updated successfully',
            'data' => $combination->load('programs'),
        ]);
    }

    public function destroy($id)
    {
        $combination = Combination::find($id);

        if (!$combination) {
            return response()->json(['status' => 'error', 'message' => 'Combination not found'], 404);
        }

        $combination->programs()->detach();
        $combination->delete();

        return response()->json(['status' => 'success', 'message' => 'Combination deleted']);
    }


    // Custom: Attach programs to an existing combination
    public function attachPrograms(Request $request, $combinationId)
    {
        $request->validate([
            'program_ids' => 'required|array',
            'program_ids.*' => 'exists:programs,id',
        ]);

        $combination = Combination::findOrFail($combinationId);
        $combination->programs()->syncWithoutDetaching($request->program_ids);

        return response()->json([
            'status' => 'success',
            'message' => 'Programs attached',
        ]);
    }

    // Custom: Detach a single program
    public function detachProgram($combinationId, $programId)
    {
        $combination = Combination::findOrFail($combinationId);
        $combination->programs()->detach($programId);

        return response()->json([
            'status' => 'success',
            'message' => 'Program detached',
        ]);
    }

    // Custom: Detach all programs
    public function detachAllPrograms($combinationId)
    {
        $combination = Combination::findOrFail($combinationId);
        $combination->programs()->detach();

        return response()->json([
            'status' => 'success',
            'message' => 'All programs detached',
        ]);
    }
}
