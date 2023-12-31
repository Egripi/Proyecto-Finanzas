<?php

namespace Model;

class Usuario extends ActiveRecord {
  protected static $tabla = 'usuarios';
  protected static $columnasDB = ['id', 'nombre', 'email', 'password', 'token', 'confirmado'];

  public $id;
  public $nombre;
  public $email;
  public $password;
  public $password2;
  public $token;
  public $confirmado;

  public function __construct($args = [])
  {
    $this->id = $args['id'] ?? null;
    $this->nombre = $args['nombre'] ?? '';
    $this->email = $args['email'] ?? '';
    $this->password = $args['password'] ?? '';
    $this->password2 = $args['password2'] ?? '';
    $this->token = $args['token'] ?? '';
    $this->confirmado = $args['confirmado'] ?? 0;
  }

  // Validar el login de usuarios
  public function validarLogin() {
    if(!$this->email) {
      self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
    }
    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      self::$alertas['error'][] = 'Email no váildo';
    }
    if(!$this->password) {
      self::$alertas['error'][] = 'El Password no puede ir vacio';
    } 

    return self::$alertas;
  }

  //Validación para cuentas nuevas
  public function validarNuevaCuenta() {
    if(!$this->nombre) {
      self::$alertas['error'][] = 'El Nombre del Usuario es Obligatorio';
    }

    if(!$this->email) {
      self::$alertas['error'][] = 'El Email del Usuario es Obligatorio';
    }

    if(!$this->password) {
      self::$alertas['error'][] = 'El Password no puede ir vacio';
    }  

    if(strlen($this->password) < 6) {
      self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
    }  

    if($this->password !== $this->password2) {
      self::$alertas['error'][] = 'Los password son diferentes';
    }

    return self::$alertas;
  }

  //Valida un email
  public function validarEmail() {
    if(!$this->email) {
      self::$alertas['error'][] = 'El Email es Obligatorio';
    }

    if(!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
      self::$alertas['error'][] = 'Email no váildo';
    }

    return self::$alertas;
  }

  //Valida el password

  public function validarPassword(){
    if(!$this->password) {
      self::$alertas['error'][] = 'El Password no puede ir vacio';
    }  

    if(strlen($this->password) < 6) {
      self::$alertas['error'][] = 'El Password debe contener al menos 6 caracteres';
    }  
    return self::$alertas;
  }

  //Hashea el password
  public function hashPassword() {
    $this->password = password_hash($this->password, PASSWORD_BCRYPT);
  }

  //Generar un token
  public function crearToken() {
    $this->token = uniqid();
  }
}
