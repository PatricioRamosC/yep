<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountForGroup extends Model
{
    use HasFactory;

    protected $table = 'Sales_Orders_count_for_group';
    protected $fillable = ['count_quantity', 'Orders_group_id', 'SKU_id'];

    public function orderGroup()
    {
        return $this->belongsTo(OrderGroup::class, 'Orders_group_id', 'id');
    }

    public function product()
    {
        return $this->belongsTo(Product::class, 'SKU_id', 'SKU');
    }

}
