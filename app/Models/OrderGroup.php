<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderGroup extends Model
{
    use HasFactory;

    protected $table = 'Sales_Orders_orders_group';
    protected $fillable = ['Dipatcher_name', 'Dipatcher_rut', 'created_date', 'Group_courier_id', 'Group_marketplace_id', 'created_by_id'];
    public $timestamps = false;

    public function courier()
    {
        return $this->belongsTo(Courier::class, 'Group_courier_id', 'Courier_name');
    }

    public function marketplace()
    {
        return $this->belongsTo(Marketplace::class, 'Group_marketplace_id', 'Marketplace_name');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'Orders_group_id', 'id');
    }

    public function countForGroup()
    {
        return $this->hasOne(CountForGroup::class, 'Orders_group_id', 'id');
    }

}
