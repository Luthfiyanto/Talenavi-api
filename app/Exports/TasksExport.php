<?php

namespace App\Exports;

use App\Models\Task;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TasksExport implements FromCollection, WithHeadings
{
    /**
     * @return \Illuminate\Support\Collection
     */

    protected $tasks;
    protected $totalTodos;
    protected $totalTimeTracked;

    public function __construct($tasks, $totalTodos, $totalTimeTracked)
    {
        $this->tasks = $tasks;
        $this->totalTodos = $totalTodos;
        $this->totalTimeTracked = $totalTimeTracked;
    }

    public function collection()
    {
        $data = $this->tasks->map(function ($task) {
            return [
                $task->title,
                $task->assignee,
                $task->due_date,
                $task->time_tracked,
                $task->status,
                $task->priority
            ];
        });

        $data->push([
            'TOTAL (' . $this->totalTodos . ' tasks)',
            '',
            '',
            $this->totalTimeTracked,
            '',
            ''
        ]);

        return $data;
    }

    public function headings(): array
    {
        return [
            'Title',
            'Assignee',
            'Due Date',
            'Time Tracked',
            'Status',
            'Priority'
        ];
    }
}
