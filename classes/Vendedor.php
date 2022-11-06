<?php

namespace App;

class Vendedor extends ActiveRecord
{
  protected static $tabla = 'vendedores';
  protected static $columnasDB = ['id', 'nombre', 'apellido', 'telefono'];
  public $id, $nombre, $apellido, $telefono;

  // constructor
  public function __construct($arg = [])
  {

    $this->id = $arg['id'] ?? null;
    $this->nombre = $arg['nombre'] ?? null;
    $this->apellido = $arg['apellido'] ?? null;
    $this->telefono = $arg['telefono'] ?? null;
  }
}
