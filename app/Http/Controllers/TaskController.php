<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
// use App\Exceptions\ApplicationException;
use Carbon\Carbon;

class TaskController extends Controller
{
    public function index()
    {
        $data = Task::all();

        return response()->json([
            'success' => true,
            'message' => 'Fetch task successfully',
            'data' => $data
        ]);
    }


    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'assignee' => 'nullable|string|max:255',
            'due_date' => 'required|date',
            'time_tracked' => 'required|numeric|min:0',
            'status' => 'required|string',
            'priority' => 'required|string'
        ]);

        // validate due_date cannot be in the past
        $due_date = Carbon::parse($request->input('due_date'))->startOfDay();
        $now = Carbon::today();

        if ($due_date->lt($now)) {
            return response()->json([
                'success' => false,
                "message" => "Due date is not allowed to be in the past"
            ])->setStatusCode(422);
        }
        $status = $request->input('status', 'pending');

        $data = Task::create([
            'title' => $request->input('title'),
            'assignee' => $request->input('assignee'),
            'due_date' => $due_date,
            'time_tracked' => $request->input('time_tracked'),
            'status' => $status,
            'priority' => $request->input('priority')
        ]);
        return response()->json([
            'success' => true,
            'message' => "Create task successfully",
            'data' => $data
        ]);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title' => 'nullable|string|max:255',
            'assignee' => 'nullable|string|max:255',
            'due_date' => 'nullable|date',
            'time_tracked' => 'nullable|numeric|min:0',
            'status' => 'nullable|string',
            'priority' => 'nullable|string'
        ]);

        // validate due_date cannot be in the past
        $due_date = Carbon::parse($request->input('due_date'))->startOfDay() ?? null;
        $now = Carbon::today();

        if ($due_date->lt($now)) {
            return response()->json([
                "success" => false,
                "message" => "Due date is not allowed to be in the past"
            ])->setStatusCode(422);
        }

        // check existing task and update
        $task = Task::find($id);

        if (empty($task)) {
            return response()->json([
                "succcess" => false,
                "message" => "Data is not found"
            ])->setStatusCode(404);
        }
        $task->update([
            'title' => $request->input('title'),
            'assignee' => $request->input('assignee'),
            'due_date' => $due_date,
            'time_tracked' => $request->input('time_tracked'),
            'status' => $request->input('status'),
            'priority' => $request->input('priority')
        ]);
        return response()->json([
            'success' => true,
            'message' => "Update task successfully",
            'data' => $task->fresh()
        ]);
    }

    public function destroy($id)
    {
        $task = Task::find($id);

        if (empty($task)) {
            return response()->json([
                "success" => false,
                "message" => "Data not found"
            ])->setStatusCode(404);
        }
        $task->delete();

        return response()->json([
            'success' => true,
            'message' => 'Delete task successfully'
        ]);
    }
}
