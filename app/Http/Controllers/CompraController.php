<?php

namespace App\Http\Controllers;

use App\Compra;
use App\DetalleCompra;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

class CompraController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request){
           $sql = trim($request->get('buscarTexto'));
           $compras = Compra::join('proveedores','compras.proveedor_id','=','proveedores.id')
               ->join('users','compras.usuario_id','=','users.id')
               ->join('detalle_compras','compras.id','=','detalle_compras.compra_id')
               ->select('compras.id','compras.tipo_identificacion',
                    'compras.num_compra','compras.fecha_compra','compras.impuesto',
                    'compras.estado','compras.total','proveedores.nombre as proveedor','users.nombre')
               ->where('compras.num_compra','LIKE','%'.$sql.'%')
               ->orderBy('compras.id','DESC')
               ->groupBy('compras.id','compras.tipo_identificacion',
                    'compras.num_compra','compras.fecha_compra','compras.impuesto',
                    'compras.estado','compras.total','proveedores.nombre','users.nombre')
               ->paginate(8);

           return view('compra.index', ["compras"=>$compras,"buscarTexto"=>$sql]);
           //return $compras;
       }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*listar las proveedores en ventana modal*/
        $proveedores = DB::table('proveedores')->get();

        /*listar los productos en ventana modal*/
        $productos = DB::table('productos as prod')
            ->select(DB::raw('CONCAT(prod.codigo," ",prod.nombre) AS producto'),'prod.id')
            ->where('prod.condicion','=','1')->get();

        return view('compra.create', [
            "proveedores"=>$proveedores,
            "productos"=>$productos
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        try
        {
            DB::beginTransaction();
            $mytime = Carbon::now('America/Mexico_City');
            $compra = new Compra();
            $compra->proveedor_id = $request->proveedor_id;
            $compra->usuario_id = \Auth::user()->id;
            $compra->tipo_identificacion = $request->tipo_identificacion;
            $compra->num_compra = $request->num_compra;
            $compra->fecha_compra = $mytime->toDateString();
            $compra->impuesto = '0.16';
            $compra->total = $request->total_pagar;
            $compra->estado = 'Registrado';
            $compra->save();
            $id_producto = $request->producto_id;
            $cantidad = $request->cantidad;
            $precio = $request->precio_compra;
            //Recorro todos los elementos
            $cont=0;
            while($cont < count((array)$id_producto))
            {
                $detalle = new DetalleCompra();
                /*enviamos valores a las propiedades del objeto detalle
                al idcompra del objeto detalle le envio el id del objeto compra,
                que es el objeto que se ingresó en la tabla compras de la bd*/
                $detalle->compra_id = $compra->id;
                $detalle->producto_id = $id_producto[$cont];
                $detalle->cantidad = $cantidad[$cont];
                $detalle->precio = $precio[$cont];
                $detalle->save();
                $cont=$cont+1;
            }
            DB::commit();
        } catch(Exception $e){
            DB::rollBack();
        }
        //return $detalle;
        return Redirect::to('compra');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //dd($id);
        /*mostrar compra*/
        //$id = $request->id;
        $compra = Compra::join('proveedores','compras.proveedor_id','=','proveedores.id')
             ->join('detalle_compras','compras.id','=','detalle_compras.compra_id')
             ->select('compras.id','compras.tipo_identificacion',
             'compras.num_compra','compras.fecha_compra','compras.impuesto',
             'compras.estado',DB::raw('sum(detalle_compras.cantidad*precio) as total'),'proveedores.nombre')
             ->where('compras.id','=',$id)
             ->orderBy('compras.id', 'desc')
             ->groupBy('compras.id','compras.tipo_identificacion',
             'compras.num_compra','compras.fecha_compra','compras.impuesto',
             'compras.estado','proveedores.nombre')
             ->first();

             /*mostrar detalles*/
             $detalles = DetalleCompra::join('productos','detalle_compras.producto_id','=','productos.id')
             ->select('detalle_compras.cantidad','detalle_compras.precio','productos.nombre as producto')
             ->where('detalle_compras.compra_id','=',$id)
             ->orderBy('detalle_compras.id', 'desc')->get();

             return view('compra.show',['compra' => $compra,'detalles' =>$detalles]);

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function edit(Compra $compra)
    {

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Compra $compra)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Compra  $compra
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $compra = Compra::findOrFail($request->compra_id);
        $compra->estado = 'Anulado';
        $compra->save();
        return Redirect::to('compra');
    }

    public function pdf(Request $request,$id)
    {
        $compra = Compra::join('proveedores','compras.proveedor_id','=','proveedores.id')
            ->join('users','compras.usuario_id','=','users.id')
            ->join('detalle_compras','compras.id','=','detalle_compras.compra_id')
            ->select('compras.id','compras.tipo_identificacion',
                'compras.num_compra','compras.created_at','compras.impuesto',DB::raw('sum(detalle_compras.cantidad*precio) as total'),
                'compras.estado','proveedores.nombre','proveedores.tipo_documento','proveedores.num_documento',
                'proveedores.direccion','proveedores.email','proveedores.telefono','users.usuario')
            ->where('compras.id','=',$id)
            ->orderBy('compras.id', 'desc')
            ->groupBy('compras.id','compras.tipo_identificacion',
                'compras.num_compra','compras.created_at','compras.impuesto',
                'compras.estado','proveedores.nombre','proveedores.tipo_documento','proveedores.num_documento',
                'proveedores.direccion','proveedores.email','proveedores.telefono','users.usuario')
            ->take(1)->get();

        $detalles = DetalleCompra::join('productos','detalle_compras.producto_id','=','productos.id')
            ->select('detalle_compras.cantidad','detalle_compras.precio',
                'productos.nombre as producto')
            ->where('detalle_compras.compra_id','=',$id)
            ->orderBy('detalle_compras.id', 'desc')->get();

        $numcompra=Compra::select('num_compra')->where('id',$id)->get();

        $pdf= \PDF::loadView('pdf.compra',['compra'=>$compra,'detalles'=>$detalles]);
        return $pdf->download('compra-'.$numcompra[0]->num_compra.'.pdf');

    }

}
