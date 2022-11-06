<?php

namespace App;

class Propiedad extends ActiveRecord
{
  protected static $tabla = 'propiedades';
  protected static $columnasDB = [
    'id',
    'titulo',
    'precio',
    'imagen',
    'descripcion',
    'habitaciones',
    'wc',
    'estacionamiento',
    'creado',
    'vendedorId',
  ];
  //* Variables de Clase
  public $id, $titulo, $precio, $imagen, $descripcion, $habitaciones, $wc, $estacionamiento, $creado, $vendedorId;

  //* constructor
  public function __construct($arg = [])
  {

    $this->id = $arg['id'] ?? null;
    $this->titulo = $arg['titulo'] ?? '';
    $this->precio = $arg['precio'] ?? '';
    $this->imagen = $arg['imagen'] ?? '';
    $this->descripcion = $arg['descripcion'] ?? '';
    $this->habitaciones = $arg['habitaciones'] ?? '';
    $this->wc = $arg['wc'] ?? '';
    $this->estacionamiento = $arg['estacionamiento'] ?? '';
    $this->creado = date('Y/m/d');
    $this->vendedorId = $arg['vendedorId'] ?? '';
  }

  //* validacion
  public function validar()
  {
    if (!$this->titulo) {
      self::$errores[] = "Debes añadir un titulo";
    }

    if (!$this->precio) {
      self::$errores[] = 'El Precio es Obligatorio';
    }

    if (strlen($this->descripcion) < 50) {
      self::$errores[] = 'La descripción es obligatoria y debe tener al menos 50 caracteres';
    }

    if (!$this->habitaciones) {
      self::$errores[] = 'El Número de habitaciones es obligatorio';
    }

    if (!$this->wc) {
      self::$errores[] = 'El Número de Baños es obligatorio';
    }

    if (!$this->estacionamiento) {
      self::$errores[] = 'El Número de lugares de Estacionamiento es obligatorio';
    }

    if (!$this->vendedorId) {
      self::$errores[] = 'Elige un vendedor';
    }

    if (!$this->imagen) {
      self::$errores[] = 'La Imagen es Obligatoria';
    }

    return self::$errores;
  }
}
