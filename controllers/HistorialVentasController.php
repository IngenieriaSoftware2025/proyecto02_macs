<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\HistorialVentas;

class HistorialVentasController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        session_start();
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'ADMIN') {
            header('Location: /proyecto02_macs/inicio');
            exit;
        }
        
        $router->render('historial/index', []);
    }

    public static function buscarAPI()
    {
        try {
            $tipo = isset($_GET['tipo']) ? $_GET['tipo'] : null;

            $condiciones = ["h.historial_situacion = 1"];

            if ($tipo && $tipo != 'todos') {
                $condiciones[] = "h.historial_tipo = '$tipo'";
            }

            $condiciones[] = "(
                (h.historial_tipo = 'venta' AND EXISTS (SELECT 1 FROM venta v WHERE v.venta_id = h.historial_referencia_id AND v.venta_situacion = 1))
                OR 
                (h.historial_tipo = 'reparacion' AND EXISTS (SELECT 1 FROM reparacion r WHERE r.reparacion_id = h.historial_referencia_id AND r.reparacion_situacion = 1))
            )";

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT h.*, c.cliente_nombre, u.usuario_nombre 
                    FROM historial_ventas h
                    INNER JOIN cliente c ON h.historial_cliente_id = c.cliente_id
                    INNER JOIN usuarios1 u ON h.historial_usuario_id = u.usuario_id
                    WHERE $where 
                    ORDER BY h.historial_fecha_creacion DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Historial obtenido correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el historial',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarDetalleAPI()
    {
        try {
            $historial_id = filter_var($_GET['historial_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($historial_id) || $historial_id < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de historial requerido'
                ]);
                exit;
            }

            $sql = "SELECT h.*, c.cliente_nombre, c.cliente_dpi, c.cliente_correo, u.usuario_nombre
                    FROM historial_ventas h
                    INNER JOIN cliente c ON h.historial_cliente_id = c.cliente_id
                    INNER JOIN usuarios1 u ON h.historial_usuario_id = u.usuario_id
                    WHERE h.historial_id = $historial_id";
            $historial = self::fetchFirst($sql);

            if (!$historial) {
                http_response_code(404);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Registro no encontrado'
                ]);
                exit;
            }

            $detalle = [];
            
            if ($historial['historial_tipo'] === 'venta') {
                $sql_detalle = "SELECT dv.*, m.marca_nombre, cel.celular_modelo
                               FROM detalle_venta dv
                               INNER JOIN inventario i ON dv.detalle_inventario_id = i.inventario_id
                               INNER JOIN celular cel ON i.inventario_celular_id = cel.celular_id
                               INNER JOIN marca m ON cel.celular_marca_id = m.marca_id
                               WHERE dv.detalle_venta_id = {$historial['historial_referencia_id']} AND dv.detalle_situacion = 1";
                $detalle = self::fetchArray($sql_detalle);
            } else {
                $sql_detalle = "SELECT reparacion_tipo_celular, reparacion_marca, reparacion_motivo, reparacion_trabajador, reparacion_servicio, reparacion_precio
                               FROM reparacion 
                               WHERE reparacion_id = {$historial['historial_referencia_id']} AND reparacion_situacion = 1";
                $detalle = self::fetchArray($sql_detalle);
            }

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Detalle obtenido correctamente',
                'historial' => $historial,
                'detalle' => $detalle
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener el detalle',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

}