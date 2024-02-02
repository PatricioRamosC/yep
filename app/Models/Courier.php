<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Courier extends Model
{
    use HasFactory;

    protected $table = 'couriers_courier';
    protected $fillable = ['Courier_image', 'Courier_name', 'referencial_description', 'STATE', 'created_date', 'created_by_id'];

    public function ordersGroup()
    {
        return $this->hasMany(OrderGroup::class, 'Group_courier_id', 'Courier_name');
    }

}
