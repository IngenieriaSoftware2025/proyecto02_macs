<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Celulares;

class CelularesController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        session_start();
        if (!isset($_SESSION['usuario_rol']) || $_SESSION['usuario_rol'] !== 'ADMIN') {
            header('Location: /proyecto02_macs/inicio');
            exit;
        }
        
        $router->render('celulares/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['celular_marca_id'] = filter_var($_POST['celular_marca_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($_POST['celular_marca_id']) || $_POST['celular_marca_id'] < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar una marca'
                ]);
                exit;
            }

            $_POST['celular_modelo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['celular_modelo']))));
            
            $cantidad_modelo = strlen($_POST['celular_modelo']);
            
            if ($cantidad_modelo < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El modelo del celular debe tener mas de 1 caracter'
                ]);
                exit;
            }

            $sql = "SELECT celular_id FROM celular WHERE celular_marca_id = '{$_POST['celular_marca_id']}' AND celular_modelo = '{$_POST['celular_modelo']}' AND celular_situacion = 1";
            $existe = self::fetchFirst($sql);

            if ($existe) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este modelo ya existe para esta marca'
                ]);
                exit;
            }
            
            $_POST['celular_fecha_creacion'] = '';
            $_POST['celular_situacion'] = 1;
            
            $celular = new Celulares($_POST);
            $resultado = $celular->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Celular registrado correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el celular',
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

            $condiciones = ["c.celular_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "c.celular_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "c.celular_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT c.*, m.marca_nombre 
                    FROM celular c
                    INNER JOIN marca m ON c.celular_marca_id = m.marca_id
                    WHERE $where 
                    ORDER BY c.celular_fecha_creacion DESC";
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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['celular_id'];
        $_POST['celular_marca_id'] = filter_var($_POST['celular_marca_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($_POST['celular_marca_id']) || $_POST['celular_marca_id'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar una marca'
            ]);
            return;
        }

        $_POST['celular_modelo'] = ucwords(strtolower(trim(htmlspecialchars($_POST['celular_modelo']))));

        $cantidad_modelo = strlen($_POST['celular_modelo']);

        if ($cantidad_modelo < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El modelo del celular debe tener mas de 1 caracter'
            ]);
            return;
        }

        $sql = "SELECT celular_id FROM celular WHERE celular_marca_id = '{$_POST['celular_marca_id']}' AND celular_modelo = '{$_POST['celular_modelo']}' AND celular_situacion = 1 AND celular_id != $id";
        $existe = self::fetchFirst($sql);

        if ($existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este modelo ya existe para esta marca'
            ]);
            return;
        }

        try {
            $data = Celulares::find($id);
            $data->sincronizar([
                'celular_marca_id' => $_POST['celular_marca_id'],
                'celular_modelo' => $_POST['celular_modelo'],
                'celular_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El celular ha sido modificado exitosamente'
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
            $ejecutar = Celulares::EliminarCelulares($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El celular ha sido eliminado correctamente'
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

    public static function buscarMarcasAPI()
    {
        try {
            $sql = "SELECT marca_id, marca_nombre 
                    FROM marca 
                    WHERE marca_situacion = 1
                    ORDER BY marca_nombre";
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
}