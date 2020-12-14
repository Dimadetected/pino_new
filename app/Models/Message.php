<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    protected $casts = [
        'created_at' => 'datetime:d.m.Y H:i',
    ];
    
    protected $with = ['user'];
    public function user(){
        return $this->belongsTo(User::class);
    }
}
