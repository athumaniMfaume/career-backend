<?php

namespace App\Http\Controllers;

use App\Models\Quiz;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function index()
    {
        return response()->json(Quiz::with('questions')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $quiz = Quiz::create($request->only('title', 'description'));

        return response()->json($quiz, 201);
    }

    public function show(Quiz $quiz)
    {
        return response()->json($quiz->load('questions.answers'));
    }

    public function update(Request $request, Quiz $quiz)
    {
        $request->validate([
            'title' => 'required|string',
            'description' => 'nullable|string',
        ]);

        $quiz->update($request->only('title', 'description'));

        return response()->json($quiz);
    }

    public function destroy(Quiz $quiz)
    {
        $quiz->questions()->delete(); // delete associated questions (cascade)
        $quiz->delete();

        return response()->json(null, 204);
    }
}
