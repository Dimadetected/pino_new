<?php

namespace App\Http\Services;

use App\Models\KanbanColumn;
use App\Models\KanbanTask;
use App\Models\Message;
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
        $array['date'] = Carbon::createFromFormat('d/m/Y H:i', $array['date'] . ' ' . $array['hours_minutes'])->format('Y-m-d H:i:s');

        $correct = '';
        if($item->name != $array['name'])
            $correct.='Название было изменено с ' . $item->name . ' на ' . $array['name'] . "<br>";
        if($item->master_id != $array['master_id'])
            $correct.='Исполнитель был изменен с ' . $item->master->name . ' на ' . User::query()->find($array['master_id'])->name;
        if($item->text != $array['text'])
            $correct.='Описание было изменено с ' . $item->text . ' на ' . $array['text'] . "<br>";
        if($item->date != $array['date'])
            $correct.='Срок исполнения было изменено с ' . $item->date . ' на ' . $array['date'] . "<br>";

        Message::query()->create([
            'type' => 'task_log',
            'external_id' => $item->id,
            'text' => $correct,
            'user_id' => auth()->user()->id
        ]);

        $item->update($array);
        return $item;
    }
}
