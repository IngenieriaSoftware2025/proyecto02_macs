<?php

namespace Controllers;

use MVC\Router;

class AppController {
    public static function index(Router $router){
        session_start();
        
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            header('Location: /proyecto02_macs/');
            exit;
        }
        
        $router->render('pages/index', []);
    }

    public static function renderInicio(Router $router){
        session_start();
        
        if (!isset($_SESSION['user']) || !isset($_SESSION['rol'])) {
            header('Location: /proyecto02_macs/');
            exit;
        }
        
        $router->render('pages/index', []);
    }
}