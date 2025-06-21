<?php

namespace App\Http\Controllers;

use App\Models\Career;
use Illuminate\Http\Request;

class CareerController extends Controller
{
    public function index()
    {
        return response()->json(Career::with('job')->get());
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'job_id' => 'required|exists:jobs,id',
        ]);

        $career = Career::create($request->all());
        return response()->json($career, 201);
    }

    public function show(Career $career)
    {
        return response()->json($career->load('job'));
    }

    public function update(Request $request, Career $career)
    {
        $request->validate([
            'name' => 'required|string',
            'job_id' => 'required|exists:jobs,id',
        ]);

        $career->update($request->all());
        return response()->json($career);
    }

    public function destroy(Career $career)
    {
        $career->delete();
        return response()->json(null, 204);
    }
}

