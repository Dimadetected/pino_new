<?php

namespace App\Http\Services;

use App\Models\Client;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;

class ClientService
{
    public function query()
    {
        return Client::query();
    }

    public function get($inn)
    {
        if ($inn != "" && $inn !== 0){
            return $this->query()->where("inn",$inn)->get();
        }
        return $this->query()->get();
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
