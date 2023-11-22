<?php

namespace Controllers;

use MVC\Router;

class SimuladorController {
  public static function index(Router $router) {
    
    session_start();

    isAuth();


    $router->render('simulador/index', [
      'titulo' => 'Simulador'
    ]);
  }
}