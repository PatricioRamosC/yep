<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Marketplace extends Model
{
    use HasFactory;

    protected $table = 'marketplaces_marketplace';
    protected $fillable = ['Marketplace_image', 'Marketplace_name', 'referencial_description', 'STATE', 'created_date', 'created_by_id'];

    public function orderGroups()
    {
        return $this->hasMany(OrderGroup::class, 'Group_marketplace_id', 'Marketplace_name');
    }

}
