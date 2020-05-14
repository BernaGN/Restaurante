<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::group(['middleware' => ['guest']], function () {
    Route::get('/','Auth\LoginController@showLoginForm');
    Route::post('/login', 'Auth\LoginController@login')->name('login');
});

Route::group(['middleware' => ['auth']], function () {
    Route::get('/home', 'HomeController@index');
    Route::post('/logout', 'Auth\LoginController@logout');
    Route::get('/listarProductoPdf', 'ProductoController@listarPdf')->name('productos_pdf');

    Route::resource('/categoria', 'CategoriaController');
    Route::resource('/producto', 'ProductoController');

    Route::group(['middleware' => ['Vendedor', 'Administrador']], function () {
        Route::resource('/cliente', 'ClienteController');
        Route::resource('/venta', 'VentaController');
        Route::get('/pdfVenta/{id}', 'VentaController@pdf');
    });

    Route::group(['middleware' => ['Comprador', 'Administrador']], function () {
        Route::resource('/proveedor', 'ProveedorController');
        Route::resource('/compra', 'CompraController');
        Route::get('/pdfCompra/{id}', 'CompraController@pdf');
    });

    Route::group(['middleware' => ['Administrador']], function () {
        Route::resource('/rol', 'RolController');
        Route::resource('/user', 'UserController');
    });

});
