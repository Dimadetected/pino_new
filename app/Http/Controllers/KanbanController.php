<?php

namespace App\Http\Controllers;

use App\Http\Services\KanbanTaskService;
use App\Models\KanbanTask;
use App\Models\User;
use Illuminate\Http\Request;

class KanbanController extends Controller
{
    public function __construct()
    {
        $this->service = new KanbanTaskService();
    }

    public function index()
    {
        $header = '<div class="row"><div class="col-6">Задачи</div><div class="text-right col-6"><a href="' . route('kanban.form') . '" class="btn btn-primary text-light">Добавить</a></div></div>';
        return view('kanban.admin.index', compact('header'));
    }

    public function form(KanbanTask $kanbanTask)
    {
        $header = '<a href="' . route('kanban.index') . '">Задачи</a> / Форма задачи';
        $masters = User::query()->get();
        return view('kanban.admin.form', compact('header', 'kanbanTask', 'masters'));
    }

    public function store(Request $request, KanbanTask $kanbanTask)
    {
        if (isset($kanbanTask->id))
            $this->service->update($kanbanTask->id, $request->except('_token'));
        else
            $this->service->store(array_merge($request->except('_token'),['user_id' => auth()->user()->id,'kanban_column_id' => 1]));
        return redirect()->route('kanban.index');
    }

    public function destroy(KanbanTask $kanbanTask)
    {
        $kanbanTask->delete();
        return redirect()->route('kanban.index');
    }

}
