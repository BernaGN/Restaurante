<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Venta extends Model
{
    protected $fillable = [
        'cliente_id',
        'usuario_id',
        'tipo_identificacion',
        'num_venta',
        'fecha_venta',
        'impuesto',
        'total',
        'estado'
    ];
}
