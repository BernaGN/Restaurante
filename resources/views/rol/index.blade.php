@extends('principal')
@section('contenido')
<main class="main">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><a href="/">SISTEMA DE COMPRAS - VENTAS</a></li>
    </ol>
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
               <h2>Listado de Roles</h2><br/>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-md-6">
                        <form action="rol" method="get" autocomplete="off">
                            @csrf
                            <div class="input-group">
                                <input type="text" name="buscarTexto" class="form-control" placeholder="Buscar texto" value="{{$buscarTexto}}">
                                <button type="submit"  class="btn btn-primary"><i class="fa fa-search"></i> Buscar</button>
                            </div>
                        </form>
                    </div>
                </div>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr class="bg-primary">
                            <th>Rol</th>
                            <th>Descripción</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($roles as $rol)
                            <tr>
                                <td>{{ $rol->nombre }}</td>
                                <td>{{ $rol->descripcion }}</td>
                                <td>
                                    @if($rol->condicion)
                                    <button type="button" class="btn btn-success btn-md">
                                        <i class="fa fa-check fa-2x"></i> Activo
                                    </button>
                                    @else
                                    <button type="button" class="btn btn-danger btn-md">
                                        <i class="fa fa-times fa-2x"></i> Desactivado
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                    </tbody>
                </table>
                {{ $roles->render() }}
            </div>
        </div>
    </div>
</main>
@endsection
