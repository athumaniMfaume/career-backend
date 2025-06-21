<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{
    // List all locations
public function index()
{
    $locations = Location::paginate(5);

    if ($locations->isEmpty()) {
        return response()->json([
            'message' => 'No locations available',
            'data' => []
        ], 200);
    }

    return response()->json($locations, 200);  // 👈 removes the extra wrapper
}


    // Store a new location
    public function store(Request $request)
    {
        $request->validate([
            'city' => 'required|string|unique:locations,city',
        ]);

        $location = Location::create([
            'city' => $request->city,
        ]);

        return response()->json([
            'message' => 'Location created successfully',
            'data' => $location
        ], 201);
    }

    // Show a single location
    public function show(Location $location)
    {
        return response()->json($location);
    }

    // Update a location
public function update(Request $request, $id)
{
    $location = Location::find($id);

    if (! $location) {
        return response()->json([
            'message' => 'Location not found'
        ], 404);
    }

    $request->validate([
        'city' => 'sometimes|string|unique:locations,city,' . $location->id,
    ]);

    if ($request->has('city')) {
        $location->city = $request->city;
        $location->save();
    }

    return response()->json([
        'message' => 'Location updated successfully',
        'data' => $location
    ], 200);
}



    // Delete a location
public function destroy($id)
{
    $location = Location::find($id);

    if (! $location) {
        return response()->json([
            'message' => 'Location not found'
        ], 404);
    }

    $location->delete();

    return response()->json([
        'message' => 'Location deleted successfully'
    ], 200);
}

}
