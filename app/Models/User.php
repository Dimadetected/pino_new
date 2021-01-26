<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'user_role_id',
        'remember_token'
    ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden = [
        'password',
//        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getBillStatusAttribute()
    {
        switch ($this->user_role_id) {
            case 2:
                return 1;
            case 3:
                return 3;
            case 4:
                return 2;
        }
        return NULL;
    }

    public function user_role(){
        return $this->belongsTo(UserRole::class);
    }
    public function organisations(){
        return $this->belongsToMany(Organisation::class);
    }

    public function getOrgIdsAttribute(){
        return $this->organisations()->pluck('organisation_id')->toArray();
    }
}
