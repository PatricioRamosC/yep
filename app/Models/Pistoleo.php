<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pistoleo extends Model
{
    use HasFactory;

    protected $table = 'pistoleos';

    protected $fillable = [
        'id_usuario',
        'etiqueta',
        'barcode',
        'quantity',
        'etapa',
    ];

}
