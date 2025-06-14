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

    public static function login()
    {
        getHeadersApi();

        try {

            $usuario_dpi = trim(htmlspecialchars($_POST['usuario_dpi']));
            $usuario_password = htmlspecialchars($_POST['usuario_password']);

            $queryExisteUser = "SELECT usuario_nombre, usuario_password, usuario_rol FROM usuarios1 WHERE usuario_dpi = '$usuario_dpi' AND usuario_situacion = 1";

            $ExisteUsuario = ActiveRecord::fetchArray($queryExisteUser);

            if ($ExisteUsuario && count($ExisteUsuario) > 0) {

                $passDB = $ExisteUsuario[0]['usuario_password'];

                if (password_verify($usuario_password, $passDB)) {

                    session_start();

                    $nombreUser = $ExisteUsuario[0]['usuario_nombre'];
                    $rolUser = $ExisteUsuario[0]['usuario_rol'];

                    $_SESSION['user'] = $nombreUser;
                    $_SESSION['rol'] = $rolUser;

                    echo json_encode([
                        'codigo' => 1,
                        'mensaje' => 'usuario ingresado exitosamente',
                    ]);
                } else {
                    echo json_encode([
                        'codigo' => 0,
                        'mensaje' => 'La contraseña que ingreso es Incorrecta',
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
        
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            header('Location: /proyecto02_macs/');
            exit;
        }
        
        $router->render('pages/index', []);
    }
}