<?php

namespace App\Http\Controllers;

use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    public function index()
    {
        return response()->json(Question::with('quiz', 'answers')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
        ]);

        $question = Question::create($request->only('quiz_id', 'question_text'));

        return response()->json($question, 201);
    }

    public function show(Question $question)
    {
        return response()->json($question->load('quiz', 'answers'));
    }

    public function update(Request $request, Question $question)
    {
        $request->validate([
            'quiz_id' => 'required|exists:quizzes,id',
            'question_text' => 'required|string',
        ]);

        $question->update($request->only('quiz_id', 'question_text'));

        return response()->json($question);
    }

    public function destroy(Question $question)
    {
        $question->answers()->delete(); // delete associated answers
        $question->delete();

        return response()->json(null, 204);
    }
}

