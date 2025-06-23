<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $table = 'ec_products';

    protected $fillable = [
        'name',
        'description',
        'price',
         'user_id',
        'image',
        'quantity',
        'sku',
        'stock_status',
        'is_featured',
        'sale_price',
        'category',
    ];

   


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'ec_product_category_product', 'product_id', 'category_id');
    }
}