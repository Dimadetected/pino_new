<?php

namespace App\Http\Services;

use App\Models\KanbanColumn;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;

class KanbanColumnService
{
    public function query()
    {
        return KanbanColumn::query();
    }

    public function get()
    {
        return $this->query()->with('tasks','tasks.user','tasks.master')->get();
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
