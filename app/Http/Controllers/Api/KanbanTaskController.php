<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\KanbanTaskAllInfoResource;
use App\Http\Resources\KanbanTaskResource;
use App\Http\Services\KanbanTaskService;
use App\Models\KanbanColumn;
use App\Models\KanbanTask;
use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class KanbanTaskController extends Controller
{
    public function __construct()
    {
        $this->service = new KanbanTaskService();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return KanbanTaskResource::collection($this->service->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function show(KanbanTask $kanbanTask)
    {
        return KanbanTaskAllInfoResource::make($kanbanTask);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function allTasksChange()
    {
        $columns = request('columns');
        $taskArr = [];

        foreach ($columns as $column)
            foreach ($column['tasks'] as $key => $task)
                $taskArr[$task['id']] = ['id' => $column['id'], 'priority' => $key];
        $user_id = \request('user_id');

        $columnsDB = KanbanColumn::query()->pluck('text', 'id')->toArray();

        DB::transaction(function () use ($taskArr, $user_id, $columnsDB) {
            $tasks = KanbanTask::query()->find(array_keys($taskArr));
            foreach ($tasks as $task) {
                if ($taskArr[$task->id]['id'] != $task->kanban_column_id)
                    Message::query()->create([
                        'type' => 'task_log',
                        'external_id' => $task->id,
                        'text' => 'Статус задачи был изменен на ' . $columnsDB[$taskArr[$task->id]['id']],
                        'user_id' => $user_id
                    ]);
                if (in_array($taskArr[$task->id]['id'], [4, 5]))
                    $success = now();
                else
                    $success = null;
                $task->update(['kanban_column_id' => $taskArr[$task->id]['id'], 'priority' => $taskArr[$task->id]['priority'],'success' => $success]);
            }
        });

        return response()->json(200);
    }

    public function message()
    {

        Message::query()->create([
            'external_id' => \request('id'),
            'type' => 'task_comment',
            'text' => \request('text'),
            'user_id' => \request('user_id')
        ]);
        return response()->json(200);
    }
}
