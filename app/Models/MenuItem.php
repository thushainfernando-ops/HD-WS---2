<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MenuItem extends Model
{
    protected $primaryKey = 'item_id';
    protected $fillable = ['name', 'description', 'price', 'category', 'image'];

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'item_id');
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class, 'item_id');
    }
}
