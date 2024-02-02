<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products_product';
    protected $fillable = ['SKU', 'Description', 'product_image', 'STATE', 'Code_bar', 'created_date', 'created_by_id', 'STOCK'];

    public function orders()
    {
        return $this->hasMany(Order::class, 'SKU_id', 'SKU');
    }
}
