<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Ventas;
use Model\DetalleVenta;
use Model\HistorialVentas;

class VentasController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('ventas/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['venta_cliente_id'] = filter_var($_POST['venta_cliente_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($_POST['venta_cliente_id']) || $_POST['venta_cliente_id'] < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un cliente'
                ]);
                exit;
            }

            $_POST['venta_usuario_id'] = filter_var($_POST['venta_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($_POST['venta_usuario_id']) || $_POST['venta_usuario_id'] < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un usuario'
                ]);
                exit;
            }

            $_POST['venta_total'] = filter_var($_POST['venta_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            
            if (empty($_POST['venta_total']) || $_POST['venta_total'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El total de la venta debe ser mayor a 0'
                ]);
                exit;
            }

            if (empty($_POST['productos_json'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe agregar al menos un producto'
                ]);
                exit;
            }

            $productos = json_decode($_POST['productos_json'], true);
            
            if (!$productos || count($productos) == 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error en los productos seleccionados'
                ]);
                exit;
            }

            foreach ($productos as $producto) {
                $sql = "SELECT inventario_cantidad FROM inventario WHERE inventario_id = {$producto['inventario_id']} AND inventario_situacion = 1";
                $stock_actual = self::fetchFirst($sql);
                
                if (!$stock_actual || $stock_actual['inventario_cantidad'] < $producto['cantidad']) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Stock insuficiente para el producto: ' . $producto['nombre']
                    ]);
                    exit;
                }
            }
            
            $_POST['venta_fecha_creacion'] = '';
            $_POST['venta_situacion'] = 1;
            
            $venta = new Ventas($_POST);
            $resultado = $venta->crear();

            if($resultado['resultado'] == 1){
                $venta_id = $resultado['id'];
                
                foreach ($productos as $producto) {
                    $detalle_data = [
                        'detalle_venta_id' => $venta_id,
                        'detalle_inventario_id' => $producto['inventario_id'],
                        'detalle_cantidad' => $producto['cantidad'],
                        'detalle_precio_unitario' => $producto['precio'],
                        'detalle_subtotal' => $producto['subtotal'],
                        'detalle_situacion' => 1
                    ];
                    
                    $detalle = new DetalleVenta($detalle_data);
                    $detalle->crear();
                    
                    $sql_update = "UPDATE inventario SET inventario_cantidad = inventario_cantidad - {$producto['cantidad']} WHERE inventario_id = {$producto['inventario_id']}";
                    self::SQL($sql_update);
                }

                $descripcion = "Venta de productos: ";
                $productos_nombres = array();
                foreach ($productos as $producto) {
                    $productos_nombres[] = $producto['nombre'];
                }
                $descripcion .= implode(', ', $productos_nombres);

                $historial_data = [
                    'historial_tipo' => 'venta',
                    'historial_referencia_id' => $venta_id,
                    'historial_cliente_id' => $_POST['venta_cliente_id'],
                    'historial_usuario_id' => $_POST['venta_usuario_id'],
                    'historial_descripcion' => $descripcion,
                    'historial_monto' => $_POST['venta_total'],
                    'historial_estado' => 'completada',
                    'historial_situacion' => 1
                ];

                $historial = new HistorialVentas($historial_data);
                $historial->crear();

                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Venta registrada correctamente',
                    'venta_id' => $venta_id
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar la venta',
                ]);
                exit;
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["v.venta_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "v.venta_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "v.venta_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT v.*, c.cliente_nombre, u.usuario_nombre,
                           (SELECT SUM(dv.detalle_cantidad) FROM detalle_venta dv WHERE dv.detalle_venta_id = v.venta_id AND dv.detalle_situacion = 1) as cantidad_total
                    FROM venta v
                    INNER JOIN cliente c ON v.venta_cliente_id = c.cliente_id
                    INNER JOIN usuarios1 u ON v.venta_usuario_id = u.usuario_id
                    WHERE $where 
                    ORDER BY v.venta_fecha_creacion DESC";
            $ventas = self::fetchArray($sql);

            foreach ($ventas as &$venta) {
                $sql_productos = "SELECT m.marca_nombre, cel.celular_modelo
                                 FROM detalle_venta dv
                                 INNER JOIN inventario i ON dv.detalle_inventario_id = i.inventario_id
                                 INNER JOIN celular cel ON i.inventario_celular_id = cel.celular_id
                                 INNER JOIN marca m ON cel.celular_marca_id = m.marca_id
                                 WHERE dv.detalle_venta_id = {$venta['venta_id']} AND dv.detalle_situacion = 1";
                $productos = self::fetchArray($sql_productos);
                
                $productos_nombres = array();
                foreach ($productos as $producto) {
                    $productos_nombres[] = $producto['marca_nombre'] . ' - ' . $producto['celular_modelo'];
                }
                $venta['productos'] = implode(', ', $productos_nombres);
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Ventas obtenidas correctamente',
                'data' => $ventas
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las ventas',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarDetalleAPI()
    {
        try {
            $venta_id = filter_var($_GET['venta_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($venta_id) || $venta_id < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de venta requerido'
                ]);
                exit;
            }

            $sql = "SELECT dv.*, m.marca_nombre, cel.celular_modelo
                    FROM detalle_venta dv
                    INNER JOIN inventario i ON dv.detalle_inventario_id = i.inventario_id
                    INNER JOIN celular cel ON i.inventario_celular_id = cel.celular_id
                    INNER JOIN marca m ON cel.celular_marca_id = m.marca_id
                    WHERE dv.detalle_venta_id = $venta_id AND dv.detalle_situacion = 1";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Detalle de venta obtenido correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el detalle de venta',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['venta_id'];
        $_POST['venta_cliente_id'] = filter_var($_POST['venta_cliente_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($_POST['venta_cliente_id']) || $_POST['venta_cliente_id'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un cliente'
            ]);
            return;
        }

        $_POST['venta_usuario_id'] = filter_var($_POST['venta_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($_POST['venta_usuario_id']) || $_POST['venta_usuario_id'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario'
            ]);
            return;
        }

        $_POST['venta_total'] = filter_var($_POST['venta_total'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        if (empty($_POST['venta_total']) || $_POST['venta_total'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El total de la venta debe ser mayor a 0'
            ]);
            return;
        }

        if (empty($_POST['productos_json'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe agregar al menos un producto'
            ]);
            return;
        }

        $productos = json_decode($_POST['productos_json'], true);
        
        if (!$productos || count($productos) == 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error en los productos seleccionados'
            ]);
            return;
        }

        try {
            $sql_detalles_antiguos = "SELECT detalle_inventario_id, detalle_cantidad 
                                     FROM detalle_venta 
                                     WHERE detalle_venta_id = $id AND detalle_situacion = 1";
            $detalles_antiguos = self::fetchArray($sql_detalles_antiguos);

            foreach ($detalles_antiguos as $detalle) {
                $sql_devolver_stock = "UPDATE inventario 
                                      SET inventario_cantidad = inventario_cantidad + {$detalle['detalle_cantidad']} 
                                      WHERE inventario_id = {$detalle['detalle_inventario_id']}";
                self::SQL($sql_devolver_stock);
            }

            $sql_anular_detalles = "UPDATE detalle_venta SET detalle_situacion = 0 WHERE detalle_venta_id = $id";
            self::SQL($sql_anular_detalles);

            foreach ($productos as $producto) {
                $sql = "SELECT inventario_cantidad FROM inventario WHERE inventario_id = {$producto['inventario_id']} AND inventario_situacion = 1";
                $stock_actual = self::fetchFirst($sql);
                
                if (!$stock_actual || $stock_actual['inventario_cantidad'] < $producto['cantidad']) {
                    http_response_code(400);
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'Stock insuficiente para el producto: ' . $producto['nombre']
                    ]);
                    return;
                }
            }

            foreach ($productos as $producto) {
                $detalle_data = [
                    'detalle_venta_id' => $id,
                    'detalle_inventario_id' => $producto['inventario_id'],
                    'detalle_cantidad' => $producto['cantidad'],
                    'detalle_precio_unitario' => $producto['precio'],
                    'detalle_subtotal' => $producto['subtotal'],
                    'detalle_situacion' => 1
                ];
                
                $detalle = new DetalleVenta($detalle_data);
                $detalle->crear();
                
                $sql_update = "UPDATE inventario SET inventario_cantidad = inventario_cantidad - {$producto['cantidad']} WHERE inventario_id = {$producto['inventario_id']}";
                self::SQL($sql_update);
            }

            $data = Ventas::find($id);
            $data->sincronizar([
                'venta_cliente_id' => $_POST['venta_cliente_id'],
                'venta_usuario_id' => $_POST['venta_usuario_id'],
                'venta_total' => $_POST['venta_total'],
                'venta_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La venta ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            $sql_detalles = "SELECT detalle_inventario_id, detalle_cantidad 
                            FROM detalle_venta 
                            WHERE detalle_venta_id = $id AND detalle_situacion = 1";
            $detalles = self::fetchArray($sql_detalles);

            foreach ($detalles as $detalle) {
                $sql_devolver_stock = "UPDATE inventario 
                                      SET inventario_cantidad = inventario_cantidad + {$detalle['detalle_cantidad']} 
                                      WHERE inventario_id = {$detalle['detalle_inventario_id']}";
                self::SQL($sql_devolver_stock);
            }

            $sql_anular_detalles = "UPDATE detalle_venta SET detalle_situacion = 0 WHERE detalle_venta_id = $id";
            self::SQL($sql_anular_detalles);

            $ejecutar = Ventas::EliminarVentas($id);

            $sql_ocultar_historial = "UPDATE historial_ventas SET historial_situacion = 0 WHERE historial_referencia_id = $id AND historial_tipo = 'venta'";
            self::SQL($sql_ocultar_historial);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La venta ha sido anulada y el stock devuelto correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al anular la venta',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarClientesAPI()
    {
        try {
            $sql = "SELECT cliente_id, cliente_nombre 
                    FROM cliente 
                    WHERE cliente_situacion = 1
                    ORDER BY cliente_nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los clientes',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarUsuariosAPI()
    {
        try {
            $sql = "SELECT usuario_id, usuario_nombre || ' - ' || usuario_puesto as usuario_nombre
                    FROM usuarios1 
                    WHERE usuario_situacion = 1 AND usuario_puesto = 'VENDEDOR'
                    ORDER BY usuario_nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Usuarios obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los usuarios',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarInventarioAPI()
    {
        try {
            $sql = "SELECT i.inventario_id, i.inventario_cantidad, i.inventario_precio, 
                           c.celular_modelo, m.marca_nombre 
                    FROM inventario i
                    INNER JOIN celular c ON i.inventario_celular_id = c.celular_id
                    INNER JOIN marca m ON c.celular_marca_id = m.marca_id
                    WHERE i.inventario_situacion = 1 AND i.inventario_cantidad > 0
                    ORDER BY m.marca_nombre, c.celular_modelo";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Inventario obtenido correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el inventario',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}