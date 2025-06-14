<?php

namespace Controllers;

use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Usuarios;

class UsuariosController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('usuarios/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['usuario_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nombre']))));
            
            $cantidad_nombre = strlen($_POST['usuario_nombre']);
            
            if ($cantidad_nombre < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El nombre del usuario debe tener mas de 1 caracter'
                ]);
                exit;
            }

            $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));
            if (strlen($_POST['usuario_dpi']) != 13) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El DPI debe tener exactamente 13 digitos'
                ]);
                exit;
            }

            $sql = "SELECT usuario_id FROM usuarios1 WHERE usuario_dpi = '{$_POST['usuario_dpi']}' AND usuario_situacion = 1";
            $existe = self::fetchFirst($sql);

            if ($existe) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Este DPI ya existe en el sistema'
                ]);
                exit;
            }

            $_POST['usuario_telefono'] = filter_var($_POST['usuario_telefono'], FILTER_SANITIZE_NUMBER_INT);
            if (strlen($_POST['usuario_telefono']) != 8) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El telefono debe tener exactamente 8 digitos'
                ]);
                exit;
            }

            $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);
            
            if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)){
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El correo electronico no es valido'
                ]);
                exit;
            }

            $_POST['usuario_puesto'] = trim(htmlspecialchars($_POST['usuario_puesto']));
            
            if (empty($_POST['usuario_puesto']) || !in_array($_POST['usuario_puesto'], ['VENDEDOR', 'GERENTE', 'CAJERO', 'TECNICO', 'SUPERVISOR'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un puesto valido'
                ]);
                exit;
            }

            $_POST['usuario_password'] = trim(htmlspecialchars($_POST['usuario_password']));
            
            $cantidad_password = strlen($_POST['usuario_password']);
            
            if ($cantidad_password < 6) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe tener al menos 6 caracteres'
                ]);
                exit;
            }

            $_POST['usuario_rol'] = trim(htmlspecialchars($_POST['usuario_rol']));
            
            if (empty($_POST['usuario_rol']) || !in_array($_POST['usuario_rol'], ['ADMIN', 'EMPLEADO'])) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un rol valido'
                ]);
                exit;
            }

            $_POST['usuario_password'] = password_hash($_POST['usuario_password'], PASSWORD_DEFAULT);
            $_POST['usuario_fecha_creacion'] = '';
            $_POST['usuario_situacion'] = 1;
            
            $usuario = new Usuarios($_POST);
            $resultado = $usuario->crear();

            if($resultado['resultado'] == 1){
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Usuario registrado correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar el usuario',
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

            $condiciones = ["usuario_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "usuario_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "usuario_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT * FROM usuarios1 WHERE $where ORDER BY usuario_fecha_creacion DESC";
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

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['usuario_id'];
        $_POST['usuario_nombre'] = ucwords(strtolower(trim(htmlspecialchars($_POST['usuario_nombre']))));

        $cantidad_nombre = strlen($_POST['usuario_nombre']);

        if ($cantidad_nombre < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El nombre del usuario debe tener mas de 1 caracter'
            ]);
            return;
        }

        $_POST['usuario_dpi'] = trim(htmlspecialchars($_POST['usuario_dpi']));
        if (strlen($_POST['usuario_dpi']) != 13) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El DPI debe tener exactamente 13 digitos'
            ]);
            return;
        }

        $sql = "SELECT usuario_id FROM usuarios1 WHERE usuario_dpi = '{$_POST['usuario_dpi']}' AND usuario_situacion = 1 AND usuario_id != $id";
        $existe = self::fetchFirst($sql);

        if ($existe) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Este DPI ya existe en el sistema'
            ]);
            return;
        }

        $_POST['usuario_telefono'] = filter_var($_POST['usuario_telefono'], FILTER_VALIDATE_INT);

        if (strlen($_POST['usuario_telefono']) != 8) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El telefono debe tener exactamente 8 digitos'
            ]);
            return;
        }

        $_POST['usuario_correo'] = filter_var($_POST['usuario_correo'], FILTER_SANITIZE_EMAIL);

        if (!filter_var($_POST['usuario_correo'], FILTER_VALIDATE_EMAIL)) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El correo electronico no es valido'
            ]);
            return;
        }

        $_POST['usuario_puesto'] = trim(htmlspecialchars($_POST['usuario_puesto']));
        
        if (empty($_POST['usuario_puesto']) || !in_array($_POST['usuario_puesto'], ['VENDEDOR', 'GERENTE', 'CAJERO', 'TECNICO', 'SUPERVISOR'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un puesto valido'
            ]);
            return;
        }

        $_POST['usuario_rol'] = trim(htmlspecialchars($_POST['usuario_rol']));
        
        if (empty($_POST['usuario_rol']) || !in_array($_POST['usuario_rol'], ['ADMIN', 'EMPLEADO'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un rol valido'
            ]);
            return;
        }

        if (!empty($_POST['usuario_password'])) {
            $_POST['usuario_password'] = trim(htmlspecialchars($_POST['usuario_password']));
            
            $cantidad_password = strlen($_POST['usuario_password']);
            
            if ($cantidad_password < 6) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La contraseña debe tener al menos 6 caracteres'
                ]);
                return;
            }
            
            $_POST['usuario_password'] = password_hash($_POST['usuario_password'], PASSWORD_DEFAULT);
        } else {
            unset($_POST['usuario_password']);
        }

        try {
            $data = Usuarios::find($id);
            $data->sincronizar([
                'usuario_nombre' => $_POST['usuario_nombre'],
                'usuario_dpi' => $_POST['usuario_dpi'],
                'usuario_telefono' => $_POST['usuario_telefono'],
                'usuario_correo' => $_POST['usuario_correo'],
                'usuario_puesto' => $_POST['usuario_puesto'],
                'usuario_rol' => $_POST['usuario_rol'],
                'usuario_situacion' => 1
            ]);

            if (isset($_POST['usuario_password'])) {
                $data->sincronizar(['usuario_password' => $_POST['usuario_password']]);
            }

            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El usuario ha sido modificado exitosamente'
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
            $ejecutar = Usuarios::EliminarUsuarios($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'El usuario ha sido eliminado correctamente'
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