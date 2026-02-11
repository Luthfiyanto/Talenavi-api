<?php

namespace App\Http\Controllers;

use App\Exports\TasksExport;
use App\Models\Task;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    public function export(Request $request)
    {
        $query = Task::query();

        // title (partial)
        $query->when($request->title, function ($q) use ($request) {
            $q->where('title', 'like', '%' . $request->title . '%');
        });

        // assignee (multiple)
        $query->when($request->assignee, function ($q) use ($request) {
            $assignees = array_map('trim', explode(',', $request->assignee));
            $q->whereIn('assignee', $assignees);
        });

        // priority (multiple)
        $query->when($request->priority, function ($q) use ($request) {
            $priorities = array_map('trim', explode(',', $request->priority));
            $q->whereIn('priority', $priorities);
        });

        // due_date range
        $query->when($request->filled('start') && $request->filled('end'), function ($q) use ($request) {
            $q->whereBetween('due_date', [$request->start, $request->end]);
        });

        // time_tracked range
        $query->when($request->filled('min') && $request->filled('max'), function ($q) use ($request) {
            $q->whereBetween('time_tracked', [$request->min, $request->max]);
        });

        // status (multiple)
        $query->when($request->status, function ($q) use ($request) {
            $statuses = array_map('trim', explode(',', $request->status));
            $q->whereIn('status', $statuses);
        });

        $tasks = $query->get();

        $totalTodos = $tasks->count();
        $totalTimeTracked = $tasks->sum('time_tracked');

        return Excel::download(
            new TasksExport($tasks, $totalTodos, $totalTimeTracked),
            'todo-report.xlsx'
        );
    }
}
