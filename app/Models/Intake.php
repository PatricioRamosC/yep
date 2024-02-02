<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Intake extends Model
{
    use HasFactory;

    protected $table = 'Intakes_intake';
    protected $fillable = ['intake_quantity', 'Provider', 'created_date', 'SKU_id', 'created_by_id', 'intake_date'];

    public function product()
    {
        return $this->belongsTo(Product::class, 'SKU_id', 'SKU');
    }

}
