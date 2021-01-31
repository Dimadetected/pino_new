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

    public function get(array $arg = [])
    {
        return $this->query()->with('tasks.user','tasks.master')->with('tasks',function ($query) use ($arg){
            if(isset($arg['master_id']) and $arg['master_id'] != 0 ) $query->where('master_id',$arg['master_id']);
            if(isset($arg['worker_id']) and $arg['worker_id'] != 0 ) $query->where('worker_id',$arg['worker_id']);
            if(isset($arg['client_id']) and $arg['client_id'] != 0 ) $query->where('user_id',$arg['client_id']);
        })->get();
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
