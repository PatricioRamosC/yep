<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GrupoPedido extends Model
{
    use HasFactory;

    protected $table = "grupo_pedidos";

    protected $fillable = [
        'id',
        'descripcion',
        'etapa',
        'id_usuario',
        'created_at',
        'updated_at'
    ];

    public function pedidos()
    {
        return $this->hasMany(Pedido::class, 'id_grupo', 'id');
    }

}
