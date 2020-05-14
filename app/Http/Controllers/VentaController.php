<?php

namespace App\Http\Controllers;

use App\Venta;
use App\DetalleVenta;
use Illuminate\Http\Request;
use Carbon\Carbon;
use DB;
use Illuminate\Support\Facades\Redirect;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request){
            $sql=trim($request->get('buscarTexto'));
            $ventas=Venta::join('clientes','ventas.cliente_id','=','clientes.id')
                ->join('users','ventas.usuario_id','=','users.id')
                ->join('detalle_ventas','ventas.id','=','detalle_ventas.venta_id')
                ->select('ventas.id','ventas.tipo_identificacion',
                    'ventas.num_venta','ventas.fecha_venta','ventas.impuesto',
                    'ventas.estado','ventas.total','clientes.nombre as cliente','users.nombre')
                ->where('ventas.num_venta','LIKE','%'.$sql.'%')
                ->orderBy('ventas.id','desc')
                ->groupBy('ventas.id','ventas.tipo_identificacion',
                    'ventas.num_venta','ventas.fecha_venta','ventas.impuesto',
                    'ventas.estado','ventas.total','clientes.nombre','users.nombre')
                ->paginate(8);
            return view('venta.index',["ventas"=>$ventas,"buscarTexto"=>$sql]);
           //return $ventas;
       }

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        /*listar las clientes en ventana modal*/
        $clientes=DB::table('clientes')->get();
        /*listar los productos en ventana modal*/
        $productos=DB::table('productos as prod')
            ->join('detalle_compras','prod.id','=','detalle_compras.producto_id')
            ->select(DB::raw('CONCAT(prod.codigo," ",prod.nombre) AS producto'),'prod.id','prod.stock','prod.precio_venta')
            ->where('prod.condicion','=','1')
            ->where('prod.stock','>','0')
            ->groupBy('producto','prod.id','prod.stock','prod.precio_venta')
            ->get();
        return view('venta.create',["clientes"=>$clientes,"productos"=>$productos]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

                 DB::beginTransaction();
                 $mytime= Carbon::now('America/Mexico_City');

                 $venta = new Venta();
                 $venta->cliente_id = $request->id_cliente;
                 $venta->usuario_id = \Auth::user()->id;
                 $venta->tipo_identificacion = $request->tipo_identificacion;
                 $venta->num_venta = $request->num_venta;
                 $venta->fecha_venta = $mytime->toDateString();
                 $venta->impuesto = "0.20";
                 $venta->total=$request->total_pagar;
                 $venta->estado = 'Registrado';
                 $venta->save();

                 $id_producto=$request->id_producto;
                 $cantidad=$request->cantidad;
                 $descuento=$request->descuento;
                 $precio=$request->precio_venta;


                 //Recorro todos los elementos
                 $cont=0;

                  while($cont < count($id_producto)){

                     $detalle = new DetalleVenta();
                     /*enviamos valores a las propiedades del objeto detalle*/
                     /*al idcompra del objeto detalle le envio el id del objeto venta, que es el objeto que se ingresó en la tabla ventas de la bd*/
                     /*el id es del registro de la venta*/
                     $detalle->venta_id = $venta->id;
                     $detalle->producto_id = $id_producto[$cont];
                     $detalle->cantidad = $cantidad[$cont];
                     $detalle->precio = $precio[$cont];
                     $detalle->descuento = $descuento[$cont];
                     $detalle->save();
                     $cont=$cont+1;
                 }

                 DB::commit();

             } catch(Exception $e){

                 DB::rollBack();
             }

             return Redirect::to('venta');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //dd($id);
        //dd($request->all());
        /*mostrar venta*/
        //$id = $request->id;
        $venta = Venta::join('clientes','ventas.cliente_id','=','clientes.id')
            ->join('detalle_ventas','ventas.id','=','detalle_ventas.venta_id')
            ->select('ventas.id','ventas.tipo_identificacion',
                'ventas.num_venta','ventas.fecha_venta','ventas.impuesto',
                'ventas.estado','clientes.nombre',
                DB::raw('sum(detalle_ventas.cantidad*precio - detalle_ventas.cantidad*precio*descuento/100) as total')
            )
            ->where('ventas.id','=',$id)
            ->orderBy('ventas.id', 'desc')
            ->groupBy('ventas.id','ventas.tipo_identificacion',
                'ventas.num_venta','ventas.fecha_venta','ventas.impuesto',
                'ventas.estado','clientes.nombre')
            ->first();
        /*mostrar detalles*/
        $detalles = DetalleVenta::join('productos','detalle_ventas.producto_id','=','productos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento','productos.nombre as producto')
            ->where('detalle_ventas.venta_id','=',$id)
            ->orderBy('detalle_ventas.id', 'desc')->get();
        return view('venta.show',['venta' => $venta,'detalles' =>$detalles]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function edit(Venta $venta)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Venta $venta)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Venta  $venta
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $venta = Venta::findOrFail($request->venta_id);
        $venta->estado = 'Anulado';
        $venta->save();
        return Redirect::to('venta');
    }

    public function pdf(Request $request,$id){
        $venta = Venta::join('clientes','ventas.cliente_id','=','clientes.id')
            ->join('users','ventas.usuario_id','=','users.id')
            ->join('detalle_ventas','ventas.id','=','detalle_ventas.venta_id')
            ->select('ventas.id','ventas.tipo_identificacion',
                'ventas.num_venta','ventas.created_at','ventas.impuesto',
                'ventas.estado',DB::raw('sum(detalle_ventas.cantidad*precio - detalle_ventas.cantidad*precio*descuento/100) as total'),'clientes.nombre','clientes.tipo_documento','clientes.num_documento',
                'clientes.direccion','clientes.email','clientes.telefono','users.usuario')
            ->where('ventas.id','=',$id)
            ->orderBy('ventas.id', 'desc')
            ->groupBy('ventas.id','ventas.tipo_identificacion',
                'ventas.num_venta','ventas.created_at','ventas.impuesto',
                'ventas.estado','clientes.nombre','clientes.tipo_documento','clientes.num_documento',
                'clientes.direccion','clientes.email','clientes.telefono','users.usuario')
            ->take(1)->get();

        $detalles = DetalleVenta::join('productos','detalle_ventas.producto_id','=','productos.id')
            ->select('detalle_ventas.cantidad','detalle_ventas.precio','detalle_ventas.descuento',
                'productos.nombre as producto')
            ->where('detalle_ventas.venta_id','=',$id)
            ->orderBy('detalle_ventas.id', 'desc')->get();

        $numventa=Venta::select('num_venta')->where('id',$id)->get();

        $pdf= \PDF::loadView('pdf.venta',['venta'=>$venta,'detalles'=>$detalles]);
        return $pdf->download('venta-'.$numventa[0]->num_venta.'.pdf');
    }

}
