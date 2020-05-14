<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
        <meta name="description" content="Sistema Compras-Ventas con Laravel y Vue Js- webtraining-it.com">
        <meta name="keyword" content="Sistema Compras-Ventas con Laravel y Vue Js">
        <title>Proyecto</title>
        <!-- Icons -->
        <link href="{{asset('css/font-awesome.min.css')}}" rel="stylesheet">
        <link href="{{asset('css/simple-line-icons.min.css')}}" rel="stylesheet">
        <!-- Main styles for this application -->
        <link href="{{asset('css/style.css')}}" rel="stylesheet">
    </head>
    <body class="app header-fixed sidebar-fixed aside-menu-fixed aside-menu-hidden">
        <header class="app-header navbar">
                <button class="navbar-toggler mobile-sidebar-toggler d-lg-none mr-auto" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <!--PONER LOGO-->
                <!--<a class="navbar-brand" href="#"></a>-->
                <button class="navbar-toggler sidebar-toggler d-md-down-none" type="button">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <ul class="nav navbar-nav d-md-down-none">
                    <li class="nav-item px-3">
                        <a class="nav-link" href="#">Dashbord</a>
                    </li>
                </ul>
                <ul class="nav navbar-nav ml-auto">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle nav-link" data-toggle="dropdown" href="#" role="button" aria-haspopup="true" aria-expanded="false">
                            <img src="{{ asset('images/users/'.Auth::user()->imagen) }}" class="img-avatar" alt="admin@bootstrapmaster.com">
                            <span class="d-md-down-none">{{Auth::user()->usuario}} </span>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right">
                            <div class="dropdown-header text-center">
                                <strong>Cuenta</strong>
                            </div>
                            <a class="dropdown-item" href="{{ url('logout') }}"
                            onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                            <i class="fa fa-lock"></i> Cerrar sesión</a>
                            <form id="logout-form" action="{{ url('logout') }}" method="POST" style="display: none;">
                                @csrf
                            </form>
                        </div>
                    </li>
                </ul>
            </header>
            <div class="app-body">
                @if(Auth::check())
                    @if(Auth::user()->rol_id == 1)
                        @include('plantilla.sidebar_Administrador')
                    @elseif(Auth::user()->rol_id == 2)
                        @include('plantilla.sidebar_Vendedor')
                    @elseif(Auth::user()->rol_id == 3)
                        @include('plantilla.sidebar_Comprador')
                    @else

                    @endif
                @endif
                @yield('contenido')
            </div>
        <footer class="app-footer">
            <span><a href="https://www.facebook.com/berna.martinezramirez" target="_blank">Bernardo Jesus Martinez R</a> &copy; 2020</span>
            <span class="ml-auto">Desarrollado por <a href="https://www.facebook.com/berna.martinezramirez" target="_blank">Berna</a></span>
        </footer>
        <!-- Bootstrap and necessary plugins -->
        <script src="{{asset('js/jquery.min.js')}}"></script>
        <script src="{{asset('js/popper.min.js')}}"></script>
        <script src="{{asset('js/bootstrap.min.js')}}"></script>
        <script src="{{asset('js/pace.min.js')}}"></script>
        <!-- Plugins and scripts required by all views -->
        <script src="{{asset('js/Chart.min.js')}}"></script>
        <!-- GenesisUI main scripts -->
        <script src="{{asset('js/template.js')}}"></script>
        <script src="{{asset('js/sweetalert2.all.min.js')}}"></script>
        @stack('scripts')
        <script type="text/javascript">
            /*EDITAR CATEGORIA EN VENTANA MODAL*/
            $('#abrirmodaleditar').on('show.bs.modal', function (event) {

               //console.log('modal abierto');

               var button = $(event.relatedTarget)
               var nombre_modal_editar = button.data('nombre')
               var descripcion_modal_editar = button.data('descripcion')
               var id_categoria = button.data('id_categoria')
               var modal = $(this)
               // modal.find('.modal-title').text('New message to ' + recipient)
               modal.find('.modal-body #nombre').val(nombre_modal_editar);
               modal.find('.modal-body #descripcion').val(descripcion_modal_editar);
               modal.find('.modal-body #id_categoria').val(id_categoria);
           })
            /******************************************************/
           /*INICIO ventana modal para cambiar estado de Categoria*/

           $('#cambiarEstado').on('show.bs.modal', function (event) {

               //console.log('modal abierto');

               var button = $(event.relatedTarget)
               var id_categoria = button.data('id_categoria')
               var modal = $(this)
               // modal.find('.modal-title').text('New message to ' + recipient)

               modal.find('.modal-body #id_categoria').val(id_categoria);
           })

           /*FIN ventana modal para cambiar estado de la categoria*/
           /*EDITAR PRODUCTO EN VENTANA MODAL*/
           $('#abrirmodalEditar').on('show.bs.modal', function (event) {
               //console.log('modal abierto');
               /*el button.data es lo que está en el button de editar*/
               var button = $(event.relatedTarget)
               /*este id_categoria_modal_editar selecciona la categoria*/
               var id_categoria_modal_editar = button.data('categoria_id')
               var nombre_modal_editar = button.data('nombre')
               var precio_venta_modal_editar = button.data('precio_venta')
               var codigo_modal_editar = button.data('codigo')
               var stock_modal_editar = button.data('stock')
               //var imagen_modal_editar = button.data('imagen1')
               var id_producto = button.data('id_producto')
               var modal = $(this)
               // modal.find('.modal-title').text('New message to ' + recipient)
               /*los # son los id que se encuentran en el formulario*/
               modal.find('.modal-body #id').val(id_categoria_modal_editar);
               modal.find('.modal-body #nombre').val(nombre_modal_editar);
               modal.find('.modal-body #precio_venta').val(precio_venta_modal_editar);
               modal.find('.modal-body #codigo').val(codigo_modal_editar);
               modal.find('.modal-body #stock').val(stock_modal_editar);
              // modal.find('.modal-body #subirImagen').html("<img src="img/producto/imagen_modal_editar">");
               modal.find('.modal-body #id_producto').val(id_producto);
           })

           $('#CambiarEstado').on('show.bs.modal', function (event) {

               //console.log('modal abierto');

               var button = $(event.relatedTarget)
               var id_producto = button.data('id_producto')
               var modal = $(this)
               // modal.find('.modal-title').text('New message to ' + recipient)

               modal.find('.modal-body #id_producto').val(id_producto);
           })

           /*FIN ventana modal para cambiar estado del producto*/

           /*EDITAR PROVEEDOR EN VENTANA MODAL*/
        $('#abrirModalEditar').on('show.bs.modal', function (event) {
               //console.log('modal abierto');
               /*el button.data es lo que está en el button de editar*/
           var button = $(event.relatedTarget)

           var nombre_modal_editar = button.data('nombre')
           var tipo_documento_modal_editar = button.data('tipo_documento')
           var num_documento_modal_editar = button.data('num_documento')
           var direccion_modal_editar = button.data('direccion')
           var telefono_modal_editar = button.data('telefono')
           var email_modal_editar = button.data('email')
           var id_proveedor = button.data('id_proveedor')
           var modal = $(this)
           // modal.find('.modal-title').text('New message to ' + recipient)
           /*los # son los id que se encuentran en el formulario*/
           modal.find('.modal-body #nombre').val(nombre_modal_editar);
           modal.find('.modal-body #tipo_documento').val(tipo_documento_modal_editar);
           modal.find('.modal-body #num_documento').val(num_documento_modal_editar);
           modal.find('.modal-body #direccion').val(direccion_modal_editar);
           modal.find('.modal-body #telefono').val(telefono_modal_editar);
           modal.find('.modal-body #email').val(email_modal_editar);
           modal.find('.modal-body #id_proveedor').val(id_proveedor);
       })
       $('#AbrirModalEditar').on('show.bs.modal', function (event) {
              //console.log('modal abierto');
              /*el button.data es lo que está en el button de editar*/
              var button = $(event.relatedTarget)

              var nombre_modal_editar = button.data('nombre')
              var tipo_documento_modal_editar = button.data('tipo_documento')
              var num_documento_modal_editar = button.data('num_documento')
              var direccion_modal_editar = button.data('direccion')
              var telefono_modal_editar = button.data('telefono')
              var email_modal_editar = button.data('email')
              var id_cliente = button.data('id_cliente')
              var modal = $(this)
              // modal.find('.modal-title').text('New message to ' + recipient)
              /*los # son los id que se encuentran en el formulario*/
              modal.find('.modal-body #nombre').val(nombre_modal_editar);
              modal.find('.modal-body #tipo_documento').val(tipo_documento_modal_editar);
              modal.find('.modal-body #num_documento').val(num_documento_modal_editar);
              modal.find('.modal-body #direccion').val(direccion_modal_editar);
              modal.find('.modal-body #telefono').val(telefono_modal_editar);
              modal.find('.modal-body #email').val(email_modal_editar);
              modal.find('.modal-body #id_cliente').val(id_cliente);
          })

              /*EDITAR USUARIO EN VENTANA MODAL*/
        $('#abrirmodalEDitar').on('show.bs.modal', function (event) {

           //console.log('modal abierto');
           /*el button.data es lo que está en el button de editar*/
           var button = $(event.relatedTarget)

           var nombre_modal_editar = button.data('nombre')
           var tipo_documento_modal_editar = button.data('tipo_documento')
           var num_documento_modal_editar = button.data('num_documento')
           var direccion_modal_editar = button.data('direccion')
           var telefono_modal_editar = button.data('telefono')
           var email_modal_editar = button.data('email')
           /*este id_rol_modal_editar selecciona la categoria*/
           var id_rol_modal_editar = button.data('rol_id')
           var usuario_modal_editar = button.data('usuario')
           //var password_modal_editar = button.data('password')
           var id_usuario = button.data('id_usuario')
           var modal = $(this)
           // modal.find('.modal-title').text('New message to ' + recipient)
           /*los # son los id que se encuentran en el formulario*/
           modal.find('.modal-body #nombre').val(nombre_modal_editar);
           modal.find('.modal-body #tipo_documento').val(tipo_documento_modal_editar);
           modal.find('.modal-body #num_documento').val(num_documento_modal_editar);
           modal.find('.modal-body #direccion').val(direccion_modal_editar);
           modal.find('.modal-body #telefono').val(telefono_modal_editar);
           modal.find('.modal-body #email').val(email_modal_editar);
           modal.find('.modal-body #rol_id').val(id_rol_modal_editar);
           modal.find('.modal-body #usuario').val(usuario_modal_editar);
           //modal.find('.modal-body #password').val(password_modal_editar);
           modal.find('.modal-body #id_usuario').val(id_usuario);
       })

       /*INICIO ventana modal para cambiar el estado del usuario*/
       $('#cambiarEstado').on('show.bs.modal', function (event) {

       //console.log('modal abierto');

       var button = $(event.relatedTarget)
       var id_usuario = button.data('id_usuario')
       var modal = $(this)
       // modal.find('.modal-title').text('New message to ' + recipient)

       modal.find('.modal-body #id_usuario').val(id_usuario);
       })

       /*FIN ventana modal para cambiar estado del usuario*/

       /*INICIO ventana modal para cambiar el estado del usuario*/

       $('#cambiarEstado').on('show.bs.modal', function (event) {

           //console.log('modal abierto');
           var button = $(event.relatedTarget)
           var id_usuario = button.data('id_usuario')
           var modal = $(this)
           // modal.find('.modal-title').text('New message to ' + recipient)
           modal.find('.modal-body #id_usuario').val(id_usuario);
       })
       /*FIN ventana modal para cambiar estado del usuario*/

       $('#CAmbiarEstadoCompra').on('show.bs.modal', function (event) {

           //console.log('modal abierto');
           var button = $(event.relatedTarget)
           var id_compra = button.data('id_compra')
           var modal = $(this)
           // modal.find('.modal-title').text('New message to ' + recipient)
           modal.find('.modal-body #id_compra').val(id_compra);
       })
       /*FIN ventana modal para cambiar estado del usuario*/
       /*INICIO ventana modal para cambiar estado de Venta*/

        $('#cambiarEstadoVenta').on('show.bs.modal', function (event) {

        //console.log('modal abierto');

        var button = $(event.relatedTarget)
        var venta_id = button.data('venta_id')
        var modal = $(this)
        // modal.find('.modal-title').text('New message to ' + recipient)

        modal.find('.modal-body #venta_id').val(venta_id);
        })

        /*FIN ventana modal para cambiar estado de la venta*/

        </script>
    </body>
</html>
