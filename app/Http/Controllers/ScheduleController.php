<?php

namespace App\Http\Controllers;

use App\Schedule;
use Illuminate\Http\Request;

class ScheduleController extends Controller
{
  public function index()
  {
    $schedules = Schedule::where('is_completed', false)
                        ->where('user_id',auth()->user()->id)
                        ->orderBy('created_at', 'desc')
                        ->withCount(['tasks' => function ($query) {
                          $query->where('is_completed', false);
                        }])
                        ->get();

    return $schedules->toJson();
  }

  public function store(Request $request)
  {
    $validatedData = $request->validate([
      'name' => 'required',
      'description' => 'required',
    ]);

    $project = Schedule::create([
      'user_id' => auth()->user()->id,
      'name' => $validatedData['name'],
      'description' => $validatedData['description'],
    ]);

    return response()->json('Schedule created!');
  }

  public function show($id)
  {
    $project = Schedule::with(['tasks' => function ($query) {
      $query->where('is_completed', false);
    }])->find($id);

    return $project->toJson();
  }

  public function markAsCompleted(Schedule $schedule)
  {
    $schedule->is_completed = true;
    $schedule->update();

    return response()->json('Schedule updated!');
  }
}
