<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use function PHPUnit\Framework\countOf;

class KanbanColumnResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $tasks = $this->tasks;
        return [
            'id' => $this->id,
            'text' => $this->text,
            'tasks' => KanbanTaskResource::collection($tasks)
        ];
    }
}
