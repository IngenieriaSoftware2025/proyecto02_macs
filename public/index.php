<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\LoginController;
use Controllers\MarcasController;
use Controllers\CelularesController;
use Controllers\ClientesController;
Use Controllers\InventarioController;
use Controllers\VentasController;
use Controllers\UsuariosController;
use Controllers\ReparacionesController;
use Controllers\HistorialVentasController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

//login
$router->get('/inicio', [AppController::class,'index']);
$router->get('/', [LoginController::class,'renderizarPagina']);
$router->post('/API/login', [LoginController::class,'login']);
$router->get('/inicio', [LoginController::class,'renderInicio']);

$router->get('/app', [AppController::class,'index']);

//marcas
$router->get('/marcas', [MarcasController::class, 'renderizarPagina']);
$router->post('/marcas/guardarAPI', [MarcasController::class, 'guardarAPI']);
$router->get('/marcas/buscarAPI', [MarcasController::class, 'buscarAPI']);
$router->post('/marcas/modificarAPI', [MarcasController::class, 'modificarAPI']);
$router->get('/marcas/eliminar', [MarcasController::class, 'EliminarAPI']);

//celulares
$router->get('/celulares', [CelularesController::class, 'renderizarPagina']);
$router->post('/celulares/guardarAPI', [CelularesController::class, 'guardarAPI']);
$router->get('/celulares/buscarAPI', [CelularesController::class, 'buscarAPI']);
$router->post('/celulares/modificarAPI', [CelularesController::class, 'modificarAPI']);
$router->get('/celulares/eliminar', [CelularesController::class, 'EliminarAPI']);
$router->get('/celulares/buscarMarcasAPI', [CelularesController::class, 'buscarMarcasAPI']);

//clientes
$router->get('/clientes', [ClientesController::class, 'renderizarPagina']);
$router->post('/clientes/guardarAPI', [ClientesController::class, 'guardarAPI']);
$router->get('/clientes/buscarAPI', [ClientesController::class, 'buscarAPI']);
$router->post('/clientes/modificarAPI', [ClientesController::class, 'modificarAPI']);
$router->get('/clientes/eliminar', [ClientesController::class, 'EliminarAPI']);

//usuarios
$router->get('/usuarios', [UsuariosController::class, 'renderizarPagina']);
$router->post('/usuarios/guardarAPI', [UsuariosController::class, 'guardarAPI']);
$router->get('/usuarios/buscarAPI', [UsuariosController::class, 'buscarAPI']);
$router->post('/usuarios/modificarAPI', [UsuariosController::class, 'modificarAPI']);
$router->get('/usuarios/eliminar', [UsuariosController::class, 'EliminarAPI']);

//inventario
$router->get('/inventario', [InventarioController::class, 'renderizarPagina']);
$router->post('/inventario/guardarAPI', [InventarioController::class, 'guardarAPI']);
$router->get('/inventario/buscarAPI', [InventarioController::class, 'buscarAPI']);
$router->post('/inventario/modificarAPI', [InventarioController::class, 'modificarAPI']);
$router->get('/inventario/eliminar', [InventarioController::class, 'EliminarAPI']);
$router->get('/inventario/buscarCelularesAPI', [InventarioController::class, 'buscarCelularesAPI']);

//ventas
$router->get('/ventas', [VentasController::class, 'renderizarPagina']);
$router->post('/ventas/guardarAPI', [VentasController::class, 'guardarAPI']);
$router->get('/ventas/buscarAPI', [VentasController::class, 'buscarAPI']);
$router->get('/ventas/buscarDetalleAPI', [VentasController::class, 'buscarDetalleAPI']);
$router->post('/ventas/modificarAPI', [VentasController::class, 'modificarAPI']);
$router->get('/ventas/eliminar', [VentasController::class, 'EliminarAPI']);
$router->get('/ventas/buscarClientesAPI', [VentasController::class, 'buscarClientesAPI']);
$router->get('/ventas/buscarUsuariosAPI', [VentasController::class, 'buscarUsuariosAPI']);
$router->get('/ventas/buscarInventarioAPI', [VentasController::class, 'buscarInventarioAPI']);

//reparaciones
$router->get('/reparaciones', [ReparacionesController::class, 'renderizarPagina']);
$router->post('/reparaciones/guardarAPI', [ReparacionesController::class, 'guardarAPI']);
$router->get('/reparaciones/buscarAPI', [ReparacionesController::class, 'buscarAPI']);
$router->post('/reparaciones/modificarAPI', [ReparacionesController::class, 'modificarAPI']);
$router->get('/reparaciones/eliminar', [ReparacionesController::class, 'EliminarAPI']);
$router->get('/reparaciones/buscarClientesAPI', [ReparacionesController::class, 'buscarClientesAPI']);
$router->get('/reparaciones/buscarEmpleadosAPI', [ReparacionesController::class, 'buscarEmpleadosAPI']);
$router->get('/reparaciones/buscarTecnicosAPI', [ReparacionesController::class, 'buscarTecnicosAPI']);
$router->get('/reparaciones/buscarUsuariosAPI', [ReparacionesController::class, 'buscarUsuariosAPI']);
$router->post('/reparaciones/iniciarAPI', [ReparacionesController::class, 'iniciarReparacionAPI']);
$router->post('/reparaciones/finalizarAPI', [ReparacionesController::class, 'finalizarReparacionAPI']);
$router->post('/reparaciones/entregarAPI', [ReparacionesController::class, 'entregarReparacionAPI']);

//historial
$router->get('/historial', [HistorialVentasController::class, 'renderizarPagina']);
$router->get('/historial/buscarAPI', [HistorialVentasController::class, 'buscarAPI']);
$router->get('/historial/buscarDetalleAPI', [HistorialVentasController::class, 'buscarDetalleAPI']);

$router->comprobarRutas();