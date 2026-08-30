<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Screenshot;
use Illuminate\Http\Request;

class ScreenShotController extends Controller
{
  public function store(Request $request)
{
    $request->validate([
        'school_id'  => 'required',
        'student_id' => 'required',
        'image'      => 'required|image',
    ]);

    $path = $request->file('image')->store('screenshots', 'public');

    $screenshot = Screenshot::create([
        'school_id'  => $request->school_id,
        'student_id' => $request->student_id,
        'image'      => $path,
    ]);

    return response()->json([
        'status'  => true,
        'message' => 'Screenshot saved successfully',
        'data'    => $screenshot,
        'image_url' => asset('storage/' . $path),
    ]);
}

}
