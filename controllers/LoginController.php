<?php

namespace Controllers;

use Model\ActiveRecord;
use MVC\Router;
use Exception;

class LoginController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('login/index', [], $layout = 'layout/layoutlogin');
    }

    public static function login() {
        getHeadersApi();
        
        try {
            $dpi = htmlspecialchars($_POST['usu_codigo']);
            $contrasena = htmlspecialchars($_POST['usu_password']);

            $queryExisteUser = "SELECT usuario_id, usuario_nombre, usuario_password, usuario_rol FROM usuarios1 WHERE usuario_dpi = '$dpi' AND usuario_situacion = 1";

            $existeUsuario = ActiveRecord::fetchArray($queryExisteUser)[0];

            if ($existeUsuario) {
                $passDB = $existeUsuario['usuario_password'];

                if (password_verify($contrasena, $passDB)) {
                    session_start();

                    $nombreCompleto = $existeUsuario['usuario_nombre'];
                    $partesNombre = explode(' ', $nombreCompleto);
                    $nombreUser = $partesNombre[0] . ' ' . ($partesNombre[2] ?? '');
                    $usuarioId = $existeUsuario['usuario_id'];
                    $rolUser = $existeUsuario['usuario_rol'];
                    
                    $_SESSION['user'] = $nombreUser;
                    $_SESSION['dpi'] = $dpi;
                    $_SESSION['usuario_id'] = $usuarioId;
                    $_SESSION['usuario_rol'] = $rolUser;

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'Usuario iniciado exitosamente',
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingreso es incorrecta',
                    ]);
                }
            } else {
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El usuario que intenta ingresar no existe',
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al intentar ingresar',
                'detalle' => $e->getMessage()
            ]);
        }
    }

    public static function renderInicio(Router $router)
    {
        session_start();
        
        if (!isset($_SESSION['user']) || !isset($_SESSION['usuario_rol'])) {
            header('Location: /proyecto02_macs/');
            exit;
        }
        
        $router->render('pages/index', []);
    }

    public static function logout(){
        session_start();
        $_SESSION = [];
        session_destroy();
        header("Location: /proyecto02_macs/");
        exit;
    }

}