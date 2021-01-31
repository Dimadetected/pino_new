<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use \Illuminate\Http\Request;

class KanbanColumn extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function tasks()
    {
        return $this->hasMany(KanbanTask::class, 'kanban_column_id');
    }

    public function scopeFilter($query, $arg)
    {
        foreach ($arg as $name => $value) {
            if ($value == 0 or $name == 'user_id')
                continue;
            $field_name = '';
            switch ($name) {
                case 'master_id':
                    $field_name = 'master_id';
                    break;
                case 'worker_id':
                    $field_name = 'worker_id';
                    break;
                case 'client_id':
                    $field_name = 'client_id';
                    break;
            }
            $query->where($field_name, $value);
        }
        return $query;
    }
}
