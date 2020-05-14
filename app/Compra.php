<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Compra extends Model
{
    protected $fillable = [
        'proveedor_id',
        'usuario_id',
        'tipo_identificacion',
        'num_compra',
        'fecha_compra',
        'impuesto',
        'total',
        'estado'
    ];

    /*es el usuario que hace el registro*/
    public function usuario()
    {
        return $this->belongsTo('App\User');
    }

    /*el proveedor que hace la compra*/
    public function proveedor()
    {
        return $this->belongsTo('App\Proveedor');
    }

}
