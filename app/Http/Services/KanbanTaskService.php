<?php

namespace App\Http\Services;

use App\Models\KanbanColumn;
use App\Models\KanbanTask;
use App\Models\Organisation;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class KanbanTaskService
{
    public function query()
    {
        return KanbanTask::query();
    }

    public function get()
    {
        return $this->query()->get();
    }

    public function store($array)
    {
        $array['date'] = Carbon::createFromFormat('d/m/Y H:i', $array['date'] . ' ' . $array['hours_minutes'])->format('Y-m-d H:i:s');
        return $this->query()->create($array);
    }

    public function update($id, $array)
    {
        $item = $this->query()->find($id);
        $item->update($array);
        return $item;
    }
}
