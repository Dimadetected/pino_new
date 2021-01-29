<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BillAction extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    protected $with = ['user','message'];


    public function user(){
        return $this->belongsTo(User::class);
    }
    public function message(){

        return $this->hasOne(Message::class,'external_id','id')->where('type','bill_action');
    }

}
