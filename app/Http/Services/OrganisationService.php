<?php

namespace App\Http\Services;

use App\Models\Organisation;
use Illuminate\Http\Request;

class OrganisationService
{
    public function query()
    {
        return Organisation::query();
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
