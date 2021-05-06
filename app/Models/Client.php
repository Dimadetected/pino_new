<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    protected $casts = [
        'file_id' => "array"
    ];

    public function file()
    {
        return $this->belongsTo(File::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class, 'client_id', 'id');
    }
}
