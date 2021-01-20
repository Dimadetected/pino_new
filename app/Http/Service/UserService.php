<?php

namespace App\Http\Service;

use App\Models\Organisation;
use App\Models\User;
use Illuminate\Http\Request;

class UserService
{
    public function query()
    {
        return User::query();
    }

    public function get()
    {
        return $this->query()->with('user_role','organisations')->get();
    }

    public function store($array)
    {
        return $this->query()->create($array);
    }

    public function update($id, $array)
    {
        $item = $this->query()->find($id);
        $item->organisations()->detach();
        $item->organisations()->attach(Organisation::query()->find($array['organisations']));
        $item->update($array);
        return $item;
    }
}
