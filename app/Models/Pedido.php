<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pedido extends Model
{
    use HasFactory;

    protected $table = "pedidos";

    protected $fillable = [
        'id',
        'id_usuario',
        'id_grupo',
        'tracking',
        'etapa',
        'etiqueta',
        'barcode',
        'cantidad',
        'created_at',
        'updated_at',
    ];

    public function grupoPedido()
    {
        return $this->belongsTo(GrupoPedido::class, 'id_grupo', 'id');
    }

}
