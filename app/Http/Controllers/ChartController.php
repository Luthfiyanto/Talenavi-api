<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Exceptions\ApplicationException;
use App\Models\Task;

class ChartController extends Controller
{
    public function getChart(Request $request)
    {
        $type = $request->query('type');

        return match ($type) {
            'status' => $this->statusSummary(),
            'priority' => $this->prioritySummary(),
            'assignee' => $this->assigneeSummary(),
            default => throw new ApplicationException('Invalid chart type', 400)
        };
    }

    public function statusSummary()
    {
        $defaultStatus = [
            'pending' => 0,
            'open' => 0,
            'in_progress' => 0,
            'completed' => 0
        ];
        $data = Task::select('status')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        $result = array_merge($defaultStatus, $data);
        return response()->json([
            "status_summary" => $result
        ]);
    }

    public function prioritySummary()
    {
        $defaultPriority = [
            'low' => 0,
            'medium' => 0,
            'high' => 0
        ];
        $data = Task::select('priority')
            ->selectRaw('COUNT(*) as total')
            ->groupBy('priority')
            ->pluck('total', 'priority');

        $result = array_merge($defaultPriority, $data);
        return response()->json([
            'priority_summary' => $result
        ]);
    }

    public function assigneeSummary()
    {
        $data = Task::selectRaw("
                assignee,
                COUNT(*) as total_todos,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as total_pending_todos,
                SUM(CASE WHEN status = 'completed' THEN time_tracked ELSE 0 END) as total_timetracked_completed_todos
            ")
            ->whereNotNull('assignee')
            ->groupBy('assignee')
            ->get()
            ->keyBy('assignee');

        $response = [];

        foreach ($data as $assignee => $row) {
            $response[$assignee] = [
                'total_todos' => (int)$row->total_todos,
                'total_pending_todos' => (int)$row->total_pending_todos,
                'total_timetracked_completed_todos' =>  (int)$row->total_timetracked_completed_todos
            ];
        }

        return response()->json([
            'assignee_summary' => $response
        ]);
    }
}
