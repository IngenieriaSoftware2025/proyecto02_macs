<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Clientes;

class ClientesController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('clientes/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['cliente_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cliente_nombre']))));
            
            $cantidad_nombre = strlen($_POST['cliente_nombre']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del cliente debe tener mas de 1 caracter'
                ]);
                exit;
            }

            $_POST['cliente_dpi'] = trim(htmlspecialchars($_POST['cliente_dpi']));
            if (strlen($_POST['cliente_dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI debe tener exactamente 13 digitos'
                ]);
                exit;
            }

            $sql = "SELECT cliente_id FROM cliente WHERE cliente_dpi = '{$_POST['cliente_dpi']}' AND cliente_situacion = 1";
            $existe = self::fetchFirst($sql);

            if ($existe) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este DPI ya existe en el sistema'
                ]);
                exit;
            }

            $_POST['cliente_telefono'] = filter_var($_POST['cliente_telefono'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($_POST['cliente_telefono']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El telefono debe tener exactamente 8 digitos'
                ]);
                exit;
            }

            $_POST['cliente_correo'] = filter_var($_POST['cliente_correo'], FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($_POST['cliente_correo'], FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electronico no es valido'
                ]);
                exit;
            }

            $_POST['cliente_direccion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cliente_direccion']))));
            
            $cantidad_direccion = strlen($_POST['cliente_direccion']);
            
            if ($cantidad_direccion < 5) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La direccion debe tener al menos 5 caracteres'
                ]);
                exit;
            }
            
            $_POST['cliente_fecha_creacion'] = '';
            $_POST['cliente_situacion'] = 1;
            
            $cliente = new Clientes($_POST);
            $resultado = $cliente->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Cliente registrado correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el cliente',
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

            $condiciones = ["cliente_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "cliente_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "cliente_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM cliente WHERE $where ORDER BY cliente_fecha_creacion DESC";
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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['cliente_id'];
        $_POST['cliente_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cliente_nombre']))));

        $cantidad_nombre = strlen($_POST['cliente_nombre']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del cliente debe tener mas de 1 caracter'
            ]);
            return;
        }

        $_POST['cliente_dpi'] = trim(htmlspecialchars($_POST['cliente_dpi']));
        if (strlen($_POST['cliente_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 digitos'
            ]);
            return;
        }

        $sql = "SELECT cliente_id FROM cliente WHERE cliente_dpi = '{$_POST['cliente_dpi']}' AND cliente_situacion = 1 AND cliente_id != $id";
        $existe = self::fetchFirst($sql);

        if ($existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este DPI ya existe en el sistema'
            ]);
            return;
        }

        $_POST['cliente_telefono'] = filter_var($_POST['cliente_telefono'], FILTER_VALIDATE_INT);

        if (strlen($_POST['cliente_telefono']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El telefono debe tener exactamente 8 digitos'
            ]);
            return;
        }

        $_POST['cliente_correo'] = filter_var($_POST['cliente_correo'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($_POST['cliente_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electronico no es valido'
            ]);
            return;
        }

        $_POST['cliente_direccion'] = ucwords(strtolower(trim(htmlspecialchars($_POST['cliente_direccion']))));
        
        $cantidad_direccion = strlen($_POST['cliente_direccion']);
        
        if ($cantidad_direccion < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La direccion debe tener al menos 5 caracteres'
            ]);
            return;
        }

        try {
            $data = Clientes::find($id);
            $data->sincronizar([
                'cliente_nombre' => $_POST['cliente_nombre'],
                'cliente_dpi' => $_POST['cliente_dpi'],
                'cliente_telefono' => $_POST['cliente_telefono'],
                'cliente_correo' => $_POST['cliente_correo'],
                'cliente_direccion' => $_POST['cliente_direccion'],
                'cliente_situacion' => 1
            ]);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El cliente ha sido modificado exitosamente'
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
            $ejecutar = Clientes::EliminarClientes($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El cliente ha sido eliminado correctamente'
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