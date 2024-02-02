<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $table = 'public.Sales_Orders_order';
    protected $fillable = ['Product_quantity', 'Order_state', 'Tracking_code', 'SKU_id', 'Orders_group_id', 'created_by_id', 'created_date'];
    public $timestamps = false;

    public function product()
    {
        return $this->belongsTo(Product::class, 'SKU_id', 'SKU');
    }

    public function orderGroup()
    {
        return $this->belongsTo(OrderGroup::class, 'Orders_group_id', 'id');
    }

}
