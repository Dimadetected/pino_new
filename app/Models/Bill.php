<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bill extends Model
{
    use HasFactory;
    protected $guarded = ['id'];
    
    protected $casts = [
        'steps' => 'array',
    ];
    
    public function goodNextStatus()
    {
        if ($this->steps == 1)
            return $this->chainOneGood();
        
        return $this->chainTwoGood();
    }
    
    /**
     * @return int|null
     * Цепочка без механика
     */
    private function chainOneGood()
    {
        switch ($this->bill_status_id) {
            case 1:
                return ['status' => 3,
                    'user_role_id' => 3];
            case 3:
                return ['status' => 8,
                    'user_role_id' => 1];
            case  8:
                return ['status' => 4,
                'user_role_id' => 4];
            case 4:
                return ['status' => 10,
                    'user_role_id' => null];
        }
        return NULL;
    }
    
    /**
     * @return int|null
     * Цепочка с механиком
     */
    private function chainTwoGood()
    {
        switch ($this->bill_status_id) {
            case 1:
                return ['status' => 3,
                    'user_role_id' => 3];
            case 3:
                return ['status' => 8,
                    'user_role_id' => 2];
            case  8:
                return ['status' => 6,
                    'user_role_id' => 1];
            case  6:
                return ['status' => 4,
                    'user_role_id' => 4];
            case 4:
                return ['status' => 10,
                    'user_role_id' => null];
        }
        return NULL;
    }
    
    public function badNextStatus()
    {
        if ($this->steps == 1)
            return $this->chainOneBad();
        
        return $this->chainTwoBad();
    }
    
    private function chainOneBad()
    {
        switch ($this->bill_status_id) {
            case 1:
                return 2;
            case 3:
                return 9;
            case  8:
                return 5;
            case 4:
                return 11;
        }
        return NULL;
    }
    
    private function chainTwoBad()
    {
        switch ($this->bill_status_id) {
            case 1:
                return 2;
            case 3:
                return 9;
            case  8:
                return 7;
            case 6:
                return 5;
            case 4:
                return 11;
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
        return $this->hasOne(BillType::class,'user_role_id','user_role_id');
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
