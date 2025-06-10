<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Marcas;

class MarcasController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('marcas/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['marca_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['marca_nombre']))));
            
            $cantidad_nombre = strlen($_POST['marca_nombre']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre de la marca debe tener mas de 1 caracter'
                ]);
                exit;
            }

            $sql = "SELECT marca_id FROM marca WHERE marca_nombre = '{$_POST['marca_nombre']}' AND marca_situacion = 1";
            $existe = self::fetchFirst($sql);

            if ($existe) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Esta marca ya existe en el sistema'
                ]);
                exit;
            }
            
            $_POST['marca_fecha_creacion'] = '';
            $_POST['marca_situacion'] = 1;
            
            $marca = new Marcas($_POST);
            $resultado = $marca->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Marca registrada correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar la marca',
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

            $condiciones = ["marca_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "marca_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "marca_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM marca WHERE $where ORDER BY marca_fecha_creacion DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Marcas obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las marcas',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['marca_id'];
        $_POST['marca_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['marca_nombre']))));

        $cantidad_nombre = strlen($_POST['marca_nombre']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre de la marca debe tener mas de 1 caracter'
            ]);
            return;
        }

        $sql = "SELECT marca_id FROM marca WHERE marca_nombre = '{$_POST['marca_nombre']}' AND marca_situacion = 1 AND marca_id != $id";
        $existe = self::fetchFirst($sql);

        if ($existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Esta marca ya existe en el sistema'
            ]);
            return;
        }

        try {
            $data = Marcas::find($id);
            $data->sincronizar([
                'marca_nombre' => $_POST['marca_nombre'],
                'marca_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La marca ha sido modificada exitosamente'
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
            $ejecutar = Marcas::EliminarMarcas($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La marca ha sido eliminada correctamente'
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
}