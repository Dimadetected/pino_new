<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Merchandising extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable = [
        'net_id',
        'address',
        'date',
        'user_id',
        'product_id',
        'balance',
        'price',
        'bottled_date',
        'comment',
        'photo_shelf',
        'photo_tsd',
        'photo_expiration_date',
        'photo_price',
    ];

    public function net()
    {
        return $this->belongsTo(Net::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
