<?php

namespace App\Http\Controllers;

use App\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use DB;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request)
        {
            $sql = trim($request->get('buscarTexto'));
            $productos = DB::table('productos as p')
                ->join('categorias as c', 'p.categoria_id', '=', 'c.id')
                ->select('p.id', 'p.categoria_id', 'p.nombre', 'p.precio_venta',
                    'p.codigo', 'p.stock', 'p.condicion', 'p.imagen', 'c.nombre as categoria')
                ->where('p.nombre','LIKE', '%'.$sql.'%')
                ->orwhere('p.codigo', 'LIKE', '%'.$sql.'%')
                ->orderBy('p.id', 'DESC')
                ->paginate(5);

            /*listar las categorias en ventana modal*/
            $categorias = DB::table('categorias')
                ->select('id','nombre','descripcion')
                ->where('condicion','=','1')->get();

            return view('producto.index', [
                "productos"=>$productos,
                "categorias"=>$categorias,
                "buscarTexto"=>$sql
            ]);
            //return $productos;
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $producto = new Producto();
        $producto->categoria_id = $request->id;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        if($archivo = $request->file('imagen'))
        {
            $archivo->move('images/productos', $archivo->getClientOriginalName());
            $producto['imagen'] = $archivo->getClientOriginalName();
        }
        $producto->save();
        return Redirect::to('producto');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $producto = Producto::findOrFail($request->id_producto);
        $producto->categoria_id = $request->id;
        $producto->codigo = $request->codigo;
        $producto->nombre = $request->nombre;
        $producto->precio_venta = $request->precio_venta;
        if($archivo = $request->file('imagen'))
        {
            $archivo->move('images', $archivo->getClientOriginalName());
            $producto['imagen'] = $archivo->getClientOriginalName();
        }
        $producto->save();
        return Redirect::to('producto');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $producto = Producto::findOrFail($request->id_producto);
        $producto->condicion = $producto->condicion ? '0' : '1';
        $producto->save();
        return Redirect::to('producto');
    }

    public function listarPdf()
    {
        $productos = Producto::join('categorias','productos.categoria_id','=','categorias.id')
            ->select('productos.id','productos.categoria_id','productos.codigo','productos.nombre','categorias.nombre as nombre_categoria','productos.stock','productos.condicion')
            ->orderBy('productos.nombre', 'desc')->get();
        $cont=Producto::count();

        $pdf= \PDF::loadView('pdf.productospdf', [
            'productos'=>$productos,
            'cont'=>$cont]
        );
        return $pdf->download('productos.pdf');
    }
}
