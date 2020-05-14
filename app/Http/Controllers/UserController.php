<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use DB;

class UserController extends Controller
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
            $usuarios = DB::table('users')
                ->join('roles', 'users.rol_id','=', 'roles.id')
                ->select('users.id', 'users.nombre', 'users.tipo_documento',
                    'users.num_documento', 'users.direccion', 'users.telefono',
                    'users.email', 'users.usuario', 'users.password',
                    'users.condicion', 'users.rol_id', 'users.imagen', 'roles.nombre as rol')
                ->where('users.nombre', 'LIKE', '%'.$sql.'%')
                ->orwhere('users.num_documento', 'LIKE', '%'.$sql.'%')
                ->orderBy('users.id', 'DESC')
                ->paginate(5);

             /*listar los roles en ventana modal*/
            $roles = DB::table('roles')
                ->select('id','nombre','descripcion')
                ->where('condicion','=','1')->get();

            return view('user.index', [
                "usuarios"=>$usuarios,
                "roles"=>$roles,
                "buscarTexto"=>$sql
            ]);
            //return $usuarios;
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
        //if(!$request->ajax()) return redirect('/');
        $user = new User();
        $user->nombre = $request->nombre;
        $user->rol_id = $request->rol_id;
        $user->tipo_documento = $request->tipo_documento;
        $user->num_documento = $request->num_documento;
        $user->telefono = $request->telefono;
        $user->email = $request->email;
        $user->direccion = $request->direccion;
        $user->usuario = $request->usuario;
        $user->password = bcrypt( $request->password);
        $user->condicion = '1';
        if($archivo = $request->file('imagen'))
        {
            $archivo->move('images/users', $archivo->getClientOriginalName());
            $user['imagen'] = $archivo->getClientOriginalName();
        }
        $user->save();
        return Redirect::to("user");

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $user = User::findOrFail($request->id_usuario);
        $user->rol_id = $request->rol_id;
        $user->nombre = $request->nombre;
        $user->tipo_documento = $request->tipo_documento;
        $user->num_documento = $request->num_documento;
        $user->telefono = $request->telefono;
        $user->email = $request->email;
        $user->direccion = $request->direccion;
        $user->usuario = $request->usuario;
        $user->password = bcrypt($request->password);
        $user->condicion = '1';
        if($archivo = $request->file('imagen'))
        {
            $archivo->move('images/users', $archivo->getClientOriginalName());
            $user['imagen'] = $archivo->getClientOriginalName();
        }
        $user->save();
        return Redirect::to("user");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = User::findOrFail($request->id_usuario);
        $user->condicion = $user->condicion ? '0' : '1';
        $user->save();
        return Redirect::to("user");
    }
}
