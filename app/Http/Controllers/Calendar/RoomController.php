<?php

namespace App\Http\Controllers\Calendar;

use App\Http\Controllers\Controller;
use App\Models\Room;
use Illuminate\Http\Request;

class RoomController extends Controller
{
    public function index()
    {
        $rooms = Room::where('client_id', client()->id)->get();
        return response()->json(['rooms'=>$rooms]);
    }
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:191',
            'no_of_bed' => 'nullable|integer|max:4',
            'description' => 'nullable|string',
        ]);
        $data['client_id'] = client()->id;
        try {
            Room::updateOrCreate(['id' => $request->id], $data);
            return response()->json(['message' => 'The information has been inserted'], 200);
        } catch (\Exception $e) {
            // return $e->getMessage();
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }

    public function destroy(Request $request)
    {
        try {
            Room::find($request->id)->delete();
            return response()->json(['message' => 'The information has been deleted'], 200);
        } catch (\Exception $e) {
            // return $e->getMessage();
            return response()->json(['message' => 'Oops something went wrong, Please try again.'], 500);
        }
    }
}
