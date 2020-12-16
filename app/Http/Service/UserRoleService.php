<?php

namespace App\Http\Service;

use App\Models\UserRole;

class UserRoleService
{
    public function query()
    {
        return UserRole::query();
    }
    
    public function get()
    {
        return $this->query()->get();
    }
}
