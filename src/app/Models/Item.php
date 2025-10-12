<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'image',
        'name',
        'brand',
        'description',
        'price',
        'condition_id',
    ];

    public static $conditions = [
        1 => '良好',
        2 => '目立った傷や汚れなし',
        3 => 'やや傷や汚れあり',
        4 => '状態が悪い',
    ];

    public function getConditionLabelAttribute()
    {
        $key = (int) $this->attributes['condition_id'] ?? null;
        return self::$conditions[$key] ?? '不明';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function condition()
    {
        return $this->belongsTo(Condition::class);
    }

    public function favorites()
    {
        return $this->hasMany(Favorite::class);
    }
    public function isFavoritedBy(?User $user)
    {
        if (!$user) return false;

        return $this->favorites()->where('user_id', $user->id)->exists();
        //return $this->belongsToMany(User::class, 'favorites', 'item_id', 'user_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'category_item', 'item_id', 'category_id')->withTimestamps();
    }

    public function purchase()
    {
        return $this->hasOne(Purchase::class);
    }
    public function isSold()
    {
        return $this->purchase()->exists();
    }
}
