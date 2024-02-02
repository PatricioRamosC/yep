<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoSku extends Model
{
    use HasFactory;

    protected $table = "grupo_sku";

    protected $fillable = [
        'id',
        'id_grupo',
        'sku',
        'cantidad',
        'created_at',
        'updated_at',
    ];

}
