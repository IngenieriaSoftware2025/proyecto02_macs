<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Inventario;

class InventarioController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('inventario/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['inventario_celular_id'] = filter_var($_POST['inventario_celular_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($_POST['inventario_celular_id']) || $_POST['inventario_celular_id'] < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un celular'
                ]);
                exit;
            }

            $sql = "SELECT inventario_id FROM inventario WHERE inventario_celular_id = '{$_POST['inventario_celular_id']}' AND inventario_situacion = 1";
            $existe = self::fetchFirst($sql);

            if ($existe) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este celular ya existe en el inventario'
                ]);
                exit;
            }

            $_POST['inventario_cantidad'] = filter_var($_POST['inventario_cantidad'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($_POST['inventario_cantidad']) || $_POST['inventario_cantidad'] < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La cantidad debe ser mayor a 0'
                ]);
                exit;
            }

            $_POST['inventario_precio'] = filter_var($_POST['inventario_precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
            
            if (empty($_POST['inventario_precio']) || $_POST['inventario_precio'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El precio debe ser mayor a 0'
                ]);
                exit;
            }
            
            $_POST['inventario_fecha_creacion'] = '';
            $_POST['inventario_situacion'] = 1;
            
            $inventario = new Inventario($_POST);
            $resultado = $inventario->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Inventario registrado correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el inventario',
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

            $condiciones = ["i.inventario_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "i.inventario_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "i.inventario_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT i.*, c.celular_modelo, m.marca_nombre 
                    FROM inventario i
                    INNER JOIN celular c ON i.inventario_celular_id = c.celular_id
                    INNER JOIN marca m ON c.celular_marca_id = m.marca_id
                    WHERE $where 
                    ORDER BY i.inventario_fecha_creacion DESC";
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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['inventario_id'];
        $_POST['inventario_celular_id'] = filter_var($_POST['inventario_celular_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($_POST['inventario_celular_id']) || $_POST['inventario_celular_id'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un celular'
            ]);
            return;
        }

        $sql = "SELECT inventario_id FROM inventario WHERE inventario_celular_id = '{$_POST['inventario_celular_id']}' AND inventario_situacion = 1 AND inventario_id != $id";
        $existe = self::fetchFirst($sql);

        if ($existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este celular ya existe en el inventario'
            ]);
            return;
        }

        $_POST['inventario_cantidad'] = filter_var($_POST['inventario_cantidad'], FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($_POST['inventario_cantidad']) || $_POST['inventario_cantidad'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La cantidad debe ser mayor a 0'
            ]);
            return;
        }

        $_POST['inventario_precio'] = filter_var($_POST['inventario_precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
        
        if (empty($_POST['inventario_precio']) || $_POST['inventario_precio'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio debe ser mayor a 0'
            ]);
            return;
        }

        try {
            $data = Inventario::find($id);
            $data->sincronizar([
                'inventario_celular_id' => $_POST['inventario_celular_id'],
                'inventario_cantidad' => $_POST['inventario_cantidad'],
                'inventario_precio' => $_POST['inventario_precio'],
                'inventario_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El inventario ha sido modificado exitosamente'
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
            $ejecutar = Inventario::EliminarInventario($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El inventario ha sido eliminado correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarCelularesAPI()
    {
        try {
            $sql = "SELECT c.celular_id, c.celular_modelo, m.marca_nombre 
                    FROM celular c
                    INNER JOIN marca m ON c.celular_marca_id = m.marca_id
                    WHERE c.celular_situacion = 1 AND m.marca_situacion = 1
                    ORDER BY m.marca_nombre, c.celular_modelo";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Celulares obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los celulares',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}