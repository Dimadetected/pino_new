<?php

namespace App\Http\Services;

use App\Models\Chain;
use App\Models\UserRole;
use Illuminate\Http\Request;

class ChainService
{
    public function query()
    {
        return Chain::query();
    }

    public function get()
    {
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
