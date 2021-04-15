<?php

namespace App\Http\Services;

use App\Models\Client;
use App\Models\Contract;
use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;

class ContractService
{
    public function query()
    {
        return Contract::query();
    }

    public function get()
    {
        return $this->query()->with('client','file')->get();
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
