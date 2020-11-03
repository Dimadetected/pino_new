<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class BillResource extends JsonResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'text' => $this->text,
            'type' => new BillTypeResource($this->bill_type),
            'status' => new BillStatusResource($this->bill_status),
            'status_bill' => $this->status,
            'file' => $this->file,
            'answer' => $this->bill_answer_id,
            'date' => Carbon::parse($this->created_at)->format('d.m.Y'),
            'acceptLink' => route('api.consult',['bill' => $this->id,'type' => 'accept','user_id' => $this->use]),
            'declineLink' => route('api.consult',['bill' => $this->id,'type' => 'decline']),
            'link' => route('bill.view',['bill' => $this->id]),
        ];
    }
}
