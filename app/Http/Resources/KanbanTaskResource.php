<?php

namespace App\Http\Resources;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class KanbanTaskResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $user = explode(' ', $this->user->name);
        $master = explode(' ', $this->master->name);
        return [
            'name' => $this->name,
            'text' => $this->text,
            'user' => $user[0] . ' ' . (isset($user[1])?$this->mb_str_split($user[1])[0]. '. ':'')  . (isset($user[2])?$this->mb_str_split($user[2])[0]. '. ':''),
            'master' => $master[0] . ' ' . (isset($master[1])?$this->mb_str_split($master[1])[0]. '. ':'')  . (isset($master[2])?$this->mb_str_split($master[2])[0]. '. ':''),
            'date' => now()->diffInHours(Carbon::parse($this->date))
        ];
    }

    function mb_str_split($str)
    {
        preg_match_all('#.{1}#uis', $str, $out);
        return $out[0];
    }
}
