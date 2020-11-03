<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    public function goodNextStatus()
    {
        switch ($this->bill_status_id) {
            case 1:
                return 2;
            case 2:
                return 3;
            case  3:
                return 4;
        }
        return NULL;
    }
    
    public function badNextStatus()
    {
        switch ($this->bill_status_id) {
            case 1:
                return 5;
            case 2:
                return 6;
        }
        return NULL;
    }
    
    public function file()
    {
        return $this->belongsTo(File::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function bill_type()
    {
        return $this->belongsTo(BillType::class);
    }
    
    public function bill_status()
    {
        return $this->belongsTo(BillStatus::class);
    }
    
    public function bill_actions()
    {
        return $this->hasMany(BillAction::class)->orderBy('created_at', 'desc');
    }
}
