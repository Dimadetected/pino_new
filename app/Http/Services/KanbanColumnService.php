<?php

namespace App\Http\Services;

use App\Models\KanbanColumn;
use App\Models\KanbanTask;
use App\Models\Organisation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KanbanColumnService
{
    public function query()
    {
        return KanbanColumn::query();
    }

    public function get(array $arg = [])
    {
            $answer = $this->query()->with('tasks.user', 'tasks.master', 'tasks.worker')->with('tasks', function ($query) use ($arg) {
                $newQ1 = KanbanTask::query();
                $newQ2 = KanbanTask::query();
                $newQ1->whereNotIn('kanban_column_id', [4, 5]);
                if (isset($arg['master_id']) and $arg['master_id'] != 0) {
                    $newQ1->where('master_id', $arg['master_id']);
                    $newQ2->where('master_id', $arg['master_id']);
                }
                if (isset($arg['worker_id']) and $arg['worker_id'] != 0) {
                    $newQ1->where('worker_id', $arg['worker_id']);
                    $newQ2->where('worker_id', $arg['worker_id']);
                }
                if (isset($arg['client_id']) and $arg['client_id'] != 0) {
                    $newQ1->where('user_id', $arg['client_id']);
                    $newQ2->where('user_id', $arg['client_id']);
                }

                $newQ2->whereIn('kanban_column_id', [4, 5])->whereBetween('success',
                    [
                        Carbon::createFromFormat("d.m.Y", $arg['date_start'])->format('Y-m-d H:i:s'),
                        Carbon::createFromFormat("d.m.Y", $arg['date_end'])->format('Y-m-d H:i:s')
                    ]);
                if (isset($arg['date_start']) and isset($arg['date_end']) and $arg['date_start'] != "" and $arg['date_end'] != "") {
                    $query->whereIn("id", array_merge($newQ1->pluck("id")->toArray(), $newQ2->pluck("id")->toArray()));
                }else {
                    $query->whereIn("id", $newQ1->pluck("id")->toArray());
                }
            })->get();

        return $answer;
    }

    public function store($array)
    {
        return $this->query()->create($array);
    }

    public function update($id, $array)
    {
        $item = $this->query()->find($id);
        $item->update($array);
        return $item;
    }
}
