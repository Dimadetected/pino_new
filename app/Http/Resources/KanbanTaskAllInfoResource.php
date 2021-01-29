<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class KanbanTaskAllInfoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'text' => $this->text,
            'user' => $this->user,
            'user_id' => $this->user_id,
            'master_id' => $this->master_id,
            'worker' => $this->worker,
            'master' => $this->master,
            'date' => $this->date,
            'comments' => MessageResource::collection($this->comments),
            'logs' => MessageResource::collection($this->logs),

        ];
    }
}
