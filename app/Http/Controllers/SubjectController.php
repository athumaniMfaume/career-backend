<?php

namespace App\Http\Controllers;

use App\Models\Subject;
use Illuminate\Http\Request;

class SubjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Subject::query();

        if ($request->has('search') && !empty($request->search)) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        $subjects = $query->paginate(5);

        if ($subjects->isEmpty()) {
            return response()->json([
                'message' => 'No subjects found.'
            ], 404);
        }

        return response()->json([
            'subjects' => $subjects
        ], 200);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:subjects,name',
        ], [
            'name.unique' => 'This subject already exists.',
        ]);

        $subject = Subject::create(['name' => $request->name]);

        return response()->json([
            'status' => 'success',
            'message' => 'Subject added successfully',
            'data' => $subject
        ], 201);
    }

    public function show($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'message' => 'Subject not found.'
            ], 404);
        }

        return response()->json($subject);
    }

    public function update(Request $request, $id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'message' => 'Subject not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'sometimes|required|string|unique:subjects,name,' . $subject->id,
        ]);

        $subject->update($request->only('name'));

        return response()->json([
            'message' => 'Subject updated successfully.',
            'subject' => $subject,
        ]);
    }

    public function destroy($id)
    {
        $subject = Subject::find($id);

        if (!$subject) {
            return response()->json([
                'message' => 'Subject not found.'
            ], 404);
        }

        $subject->delete();

        return response()->json([
            'message' => 'Subject deleted successfully.',
            'subject' => $subject,
        ]);
    }
}


