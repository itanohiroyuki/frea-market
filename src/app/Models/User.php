<?php

namespace App\Models;

use App\Models\Comment;
use App\Models\Product;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function purchasedProducts()
    {
        return $this->hasMany(Product::class, 'buyer_id');
    }

    // public function likes()
    // {
    //     return $this->belongsToMany(Product::class, 'likes', 'user_id', 'product_id')
    //         ->withTimestamps();
    // }

    public function likedProducts()
    {
        return $this->belongsToMany(Product::class, 'likes', 'user_id', 'product_id')
            ->withTimestamps();
    }

    public function  profile()
    {
        return $this->hasOne(Profile::class);
    }
}
