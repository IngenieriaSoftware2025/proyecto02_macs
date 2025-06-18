<?php

namespace Controllers;

use Exception;
use Model\ActiveRecord;
use MVC\Router;

class EstadisticasController extends ActiveRecord
{

   public static function renderizarPagina(Router $router)
   {
       session_start();
       if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'ADMIN') {
           header('Location: /proyecto02_macs/inicio');
           exit;
       }
       
       $router->render('estadisticas/index', []);
   }

   public static function buscarMarcasVendidasAPI(){
       try {
           $sql = "SELECT m.marca_nombre as marca, COUNT(dv.detalle_cantidad) as cantidad 
                   FROM detalle_venta dv 
                   INNER JOIN inventario i ON dv.detalle_inventario_id = i.inventario_id 
                   INNER JOIN celular c ON i.inventario_celular_id = c.celular_id 
                   INNER JOIN marca m ON c.celular_marca_id = m.marca_id 
                   WHERE dv.detalle_situacion = 1 
                   GROUP BY m.marca_nombre 
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Marcas más vendidas obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las marcas',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarEstadosReparacionesAPI(){
       try {
           $sql = "SELECT reparacion_estado as estado, COUNT(*) as cantidad
                   FROM reparacion 
                   WHERE reparacion_situacion = 1
                   GROUP BY reparacion_estado
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Estados de reparaciones obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los estados',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarClientesFrecuentesAPI(){
       try {
           $sql = "SELECT c.cliente_nombre as cliente, COUNT(v.venta_id) as cantidad
                   FROM venta v
                   INNER JOIN cliente c ON v.venta_cliente_id = c.cliente_id
                   WHERE v.venta_situacion = 1 AND c.cliente_situacion = 1
                   GROUP BY c.cliente_id, c.cliente_nombre
                   ORDER BY cantidad DESC
                   LIMIT 10";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Clientes más frecuentes obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los clientes',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarEmpleadosProductivosAPI(){
       try {
           $sql = "SELECT u.usuario_nombre as empleado, COUNT(v.venta_id) as ventas
                   FROM venta v
                   INNER JOIN usuarios1 u ON v.venta_usuario_id = u.usuario_id
                   WHERE v.venta_situacion = 1 AND u.usuario_situacion = 1
                   GROUP BY u.usuario_id, u.usuario_nombre
                   ORDER BY ventas DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Empleados más productivos obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los empleados',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarStockCriticoAPI(){
       try {
           $sql = "SELECT (m.marca_nombre || ' - ' || c.celular_modelo) as producto, 
                          i.inventario_cantidad as stock
                   FROM inventario i
                   INNER JOIN celular c ON i.inventario_celular_id = c.celular_id
                   INNER JOIN marca m ON c.celular_marca_id = m.marca_id
                   WHERE i.inventario_situacion = 1
                   ORDER BY i.inventario_cantidad ASC
                   LIMIT 10";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Stock crítico obtenido correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener el stock',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarServiciosReparacionAPI(){
       try {
           $sql = "SELECT reparacion_servicio as servicio, COUNT(*) as cantidad
                   FROM reparacion 
                   WHERE reparacion_situacion = 1
                   GROUP BY reparacion_servicio
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Servicios más solicitados obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los servicios',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarVentasMensualesAPI(){
       try {
           $sql = "SELECT c.cliente_nombre as cliente, v.venta_total as total
                   FROM venta v
                   INNER JOIN cliente c ON v.venta_cliente_id = c.cliente_id
                   WHERE v.venta_situacion = 1 AND c.cliente_situacion = 1
                   ORDER BY v.venta_total DESC
                   LIMIT 10";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Ventas por cliente obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las ventas por cliente',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarCelularesMasVendidosAPI(){
       try {
           $sql = "SELECT (m.marca_nombre || ' ' || c.celular_modelo) as producto, 
                          SUM(dv.detalle_cantidad) as vendidos
                   FROM detalle_venta dv
                   INNER JOIN inventario i ON dv.detalle_inventario_id = i.inventario_id
                   INNER JOIN celular c ON i.inventario_celular_id = c.celular_id
                   INNER JOIN marca m ON c.celular_marca_id = m.marca_id
                   WHERE dv.detalle_situacion = 1
                   GROUP BY c.celular_id, m.marca_nombre, c.celular_modelo
                   ORDER BY vendidos DESC
                   LIMIT 10";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Celulares más vendidos obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los celulares',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarReparacionesPorMarcaAPI(){
       try {
           $sql = "SELECT reparacion_marca as marca, COUNT(*) as cantidad
                   FROM reparacion 
                   WHERE reparacion_situacion = 1
                   GROUP BY reparacion_marca
                   ORDER BY cantidad DESC";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Reparaciones por marca obtenidas correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener las reparaciones por marca',
               'detalle' => $e->getMessage()
           ]);
       }
   }

   public static function buscarIngresosPorTipoAPI(){
       try {
           $sql = "SELECT 'Ventas' as tipo, SUM(venta_total) as total
                   FROM venta 
                   WHERE venta_situacion = 1
                   UNION ALL
                   SELECT 'Reparaciones' as tipo, SUM(reparacion_precio) as total
                   FROM reparacion 
                   WHERE reparacion_situacion = 1";
           $data = self::fetchArray($sql);

           http_response_code(200);
           echo json_encode([
               'codigo' => 1,
               'mensaje' => 'Ingresos por tipo obtenidos correctamente',
               'data' => $data
           ]);
       } catch (Exception $e) {
           http_response_code(400);
           echo json_encode([
               'codigo' => 0,
               'mensaje' => 'Error al obtener los ingresos',
               'detalle' => $e->getMessage()
           ]);
       }
   }
}