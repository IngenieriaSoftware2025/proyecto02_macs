<?php 
require_once __DIR__ . '/../includes/app.php';


use MVC\Router;
use Controllers\AppController;
use Controllers\MarcasController;
use Controllers\CelularesController;
use Controllers\ClientesController;
Use Controllers\InventarioController;

$router = new Router();
$router->setBaseURL('/' . $_ENV['APP_NAME']);

$router->get('/', [AppController::class,'index']);

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

//inventario
$router->get('/inventario', [InventarioController::class, 'renderizarPagina']);
$router->post('/inventario/guardarAPI', [InventarioController::class, 'guardarAPI']);
$router->get('/inventario/buscarAPI', [InventarioController::class, 'buscarAPI']);
$router->post('/inventario/modificarAPI', [InventarioController::class, 'modificarAPI']);
$router->get('/inventario/eliminar', [InventarioController::class, 'EliminarAPI']);
$router->get('/inventario/buscarCelularesAPI', [InventarioController::class, 'buscarCelularesAPI']);

// Comprueba y valida las rutas, que existan y les asigna las funciones del Controlador
$router->comprobarRutas();
