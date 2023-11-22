<?php

namespace Controllers;

use Classes\Email;
use Model\Usuario;
use MVC\Router;

class LoginController {
  public static function login(Router $router) {

    if($_SERVER['REQUEST_METHOD'] == 'POST') {

      $alertas = [];
      $usuario = new Usuario($_POST);

      $alertas = $usuario->validarLogin();

      if(empty($alertas)) {
        //Verificar que el usuario exista
        $usuario = Usuario::where('email', $usuario->email);

        if(!$usuario || !$usuario->confirmado) {
          Usuario::setAlerta('error', 'El Usuario No Existe o no está confirmado');
        } else {
          //El usuario existe
          if( password_verify($_POST['password'], $usuario->password) ) {

            //Iniciar Sesión
            session_start();
            $_SESSION['id'] = $usuario->id;
            $_SESSION['nombre'] = $usuario->nombre;
            $_SESSION['email'] = $usuario->email;
            $_SESSION['login'] = true;

            //Redireccionar
            header('Location: /simulador');

            debuguear($_SESSION);
          } else {
            Usuario::setAlerta('error', 'Password Incorrecto');
          }
        }


      }

    }
    $alertas = Usuario::getAlertas();
    //render a la vista
    $router->render('auth/login', [
      'titulo' => 'Iniciar Sesión',
      'alertas' => $alertas
    ]);
  }

  public static function logout() {
    session_start();
    $_SESSION = [];
    header('Location: /');
  }

  public static function crear(Router $router) {
    $alertas = [];
    $usuario = new Usuario;

    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $usuario->sincronizar($_POST);
      $alertas = $usuario->validarNuevaCuenta();

      if(empty($alertas)) {
        $existeUsuario = Usuario::where('email', $usuario->email);

        if($existeUsuario) {
          Usuario::setAlerta('error', 'El usuario ya esta registrado');
          $alertas = Usuario::getAlertas();
        } else {
          //hashear el password
          $usuario->hashPassword();

          //eliminar password2
          unset($usuario->password2);

          //Generar el token
          $usuario->crearToken();

          //Crear nuevo usuario
          $resultado = $usuario->guardar();

          //Enviar email
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
          $email->enviarConfirmacion();

          if($resultado) {
            header('Location: /mensaje');
          }

        }
      }
  
    }

    //render a la vista
    $router->render('auth/crear', [
      'titulo' => 'Crea tu cuenta',
      'usuario' => $usuario,
      'alertas' => $alertas
    ]);
  }

  public static function olvide(Router $router) {
    $alertas = [];
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
      $usuario = new Usuario($_POST);
      $alertas = $usuario->validarEmail();

      if(empty($alertas)) {
        //Buscar el usuario
        $usuario = Usuario::where('email', $usuario->email);

        if($usuario && $usuario->confirmado) {
          //Generar un nuevo token
          $usuario->crearToken();
          unset($usuario->password2);

          //Actualizar el ususario
          $usuario->guardar();

          //Enviar el email
          $email = new Email($usuario->email, $usuario->nombre, $usuario->token);
          $email->enviarInstrucciones();

          //Imprimir la alerta
          usuario::setAlerta('exito', 'Hemos enviado las instrucciones a tu email');
          
        } else {
          Usuario::setAlerta('error', 'El usuario no existe o no esta confirmado');
          
        }
      }
    }
    
    $alertas = Usuario::getAlertas();

    //render a la vista
    $router->render('auth/olvide', [
      'titulo' => 'Olvide mi Password',
      'alertas' => $alertas
    ]);
  }

  public static function reestablecer(Router $router) {

    $token = s($_GET['token']);
    $mostrar = true;
    

    if(!$token) header('Location: /');

    //Identificar al usuario con este token
    $usuario = Usuario::where('token', $token);

    if(empty($usuario)) {
      Usuario::setAlerta('error', 'Token no Válido');
      $mostrar = false;
    }


    if($_SERVER['REQUEST_METHOD'] == 'POST') {

      //Añadir el nuevo password
      $usuario->sincronizar($_POST);

      //Validar password
      $alertas = $usuario->validarPassword();

      if(empty($alertas)) {
        //Hashear el nuevo password
        $usuario->hashPassword();

        //Eliminar token
        $usuario->token = null;

        //Guardar usuario en la DB
        $resultado = $usuario->guardar();

        //Redireccionar
        if($resultado) {
          header('Location: /');
        }
      }

    }

    $alertas = Usuario::getAlertas();
    //render a la vista
    $router->render('auth/reestablecer', [
      'titulo' => 'Reestablecer Password',
      'alertas' => $alertas,
      'mostrar' => $mostrar
    ]);
  }

  public static function mensaje(Router $router) {
    $router->render('auth/mensaje', [
      'titulo' => 'Cuenta Creada Existosamente'
    ]);
  }

  public static function confirmar(Router $router) {

    $token = s($_GET['token']);

    if(!$token) header('Location: /');

    //Encontrar al usuario con este token
    $usuario = Usuario::where('token', $token);

    if(empty($usuario)) {
      //No se encontró un usuario con ese token
      Usuario::setAlerta('error', 'Token no Válido');
    } else {
      //Confirmar la cuenta
      $usuario->confirmado = 1;
      $usuario->token = "";
      unset($usuario->password2);
      
      //Guardar en la BD
      $usuario->guardar();

      Usuario::setAlerta('exito', 'Cuenta comprobada Correctamente');

    }

    $alertas = Usuario::getAlertas();

    $router->render('auth/confirmar', [
      'titulo' => 'Confirma tu cuenta',
      'alertas' => $alertas
    ]);
  }
}
?>