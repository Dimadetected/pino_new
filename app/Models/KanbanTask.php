<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KanbanTask extends Model
{
    use HasFactory;
    protected $guarded = ['id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function master()
    {
        return $this->belongsTo(User::class,'master_id','id');
    }
    public function worker()
    {
        return $this->belongsTo(User::class,'worker_id','id');
    }
    public function comments()
    {
        return $this->hasMany(Message::class,'external_id','id')->where('type','task_comment')->orderByDesc('id');
    }
    public function logs()
    {
        return $this->hasMany(Message::class,'external_id','id')->where('type','task_log')->orderByDesc('id');
    }

    protected $casts = [
        'date'  => 'date:d.m.Y H:i',
        'success' => 'date:d.m.Y H:i',
    ];
}
