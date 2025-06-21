<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ProgramController extends Controller
{
    public function index()
    {
        $programs = Program::all();

        if ($programs->isEmpty()) {
            return response()->json([
                'status' => 'empty',
                'message' => 'No programs found.',
                'data' => [],
            ], 200);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Programs retrieved successfully.',
            'data' => $programs,
        ], 200);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:programs,name',
            'level' => ['required', Rule::in(['certificate', 'diploma', 'degree'])],
        ]);

        $program = Program::create($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Program created successfully.',
            'data' => $program,
        ], 201);
    }

    public function show($id)
    {
        $program = Program::find($id);

        if (!$program) {
            return response()->json([
                'status' => 'error',
                'message' => 'Program not found.',
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Program retrieved successfully.',
            'data' => $program,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $program = Program::find($id);

        if (!$program) {
            return response()->json([
                'status' => 'error',
                'message' => 'Program not found.',
            ], 404);
        }

        $validated = $request->validate([
            'name' => [
                'sometimes',
                'required',
                'string',
                'max:255',
                Rule::unique('programs', 'name')->ignore($program->id),
            ],
            'level' => ['sometimes', 'required', Rule::in(['certificate', 'diploma', 'degree'])],
        ]);

        $program->update($validated);

        return response()->json([
            'status' => 'success',
            'message' => 'Program updated successfully.',
            'data' => $program,
        ], 200);
    }

    public function destroy($id)
    {
        $program = Program::find($id);

        if (!$program) {
            return response()->json([
                'status' => 'error',
                'message' => 'Program not found.',
            ], 404);
        }

        $program->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Program deleted successfully.',
        ], 200);
    }
}

