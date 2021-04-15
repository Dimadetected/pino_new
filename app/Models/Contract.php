<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Contract extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    CONST IDS = [45,29,3,0];
    CONST LAST_ID = self::IDS[3];
    public function nextStep()
    {
        switch ($this->status) {
            case null:
                return self::IDS[0];
            case self::IDS[0]:
                return self::IDS[1];
            case self::IDS[1]:
                return self::IDS[2];
            case self::IDS[2]:
                return 0;
        }
        return 3;
    }

    public function client(){
        return $this->belongsTo(Client::class);
    }
    public function file(){
        return $this->belongsTo(File::class);
    }
}
