<?php

namespace App;

class Propiedad
{
  //* Base de datos
  protected static $db;
  //* columnas de la base de datos
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
  // errores
  protected static $errores = [];

  //* Variables de Clase
  public $id;
  public $titulo;
  public $precio;
  public $imagen;
  public $descripcion;
  public $habitaciones;
  public $wc;
  public $estacionamiento;
  public $creado;
  public $vendedorId;

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
    $this->vendedorId = $arg['vendedorId'] ?? 1;
  }

  // * Función para conectar a la base de datos
  public static function setDB($database)
  {
    self::$db = $database;
  }


  // * Sanitizar los datos
  public function sanitizarAtributos()
  {
    $atributos = $this->atributos();
    $sanitizado = [];

    foreach ($atributos as $key => $value) {
      $sanitizado[$key] = self::$db->escape_string($value);
    }

    return $sanitizado;
  }

  //* Subida de archivos
  public function setImagen($imagen)
  {
    // elimina la imagen previa
    if (!is_null($this->id)) {
      $this->borrarImagen();
    }

    // asignar al atributo de imagen el nombre de la imagen
    if ($imagen) {
      $this->imagen = $imagen;
    }
  }

  //*borra imagen del servidor
  public function borrarImagen()
  {
    // comprobar si existe el archivo
    $existeArchivo = file_exists(CARPETA_IMAGENES . $this->imagen);

    if ($existeArchivo) {
      unlink(CARPETA_IMAGENES . $this->imagen);
    }
  }

  //* identificar y unir los atributos de la Base <d></d>e Datos
  public function atributos()
  {
    $atributos = [];
    foreach (self::$columnasDB as $columna) {
      if ($columna === 'id') continue;
      $atributos[$columna] = $this->$columna;
    }

    return $atributos;
  }

  //* Eliminar un registro
  public function eliminar()
  {
    // Eliminar la propiedad
    $query = "DELETE FROM propiedades WHERE id = " . self::$db->escape_string($this->id) . " LIMIT 1";
    $resultado = self::$db->query($query);
    if ($resultado) {
      $this->borrarImagen();
      header('location: /admin?resultado=3');
    }
  }

  public function guardar()
  {
    if (!is_null($this->id)) {
      // actualizar
      $this->actualizar();
    } else {
      // creando registro
      $this->crear();
    }
  }

  // * Función para guardar en la base de datos
  public function crear()
  {
    // sanitizar datos
    $atributos = $this->sanitizarAtributos();

    $query = "INSERT INTO propiedades ( ";
    $query .= join(", ", array_keys($atributos));
    $query .= " ) VALUES (' ";
    $query .= join("' , '", array_values($atributos)) . "' )";

    $resultado = self::$db->query($query);

    // mensaje exito
    if ($resultado) {
      // Redireccionar al usuario.
      header('Location: /admin?resultado=1');
    }
  }

  //* actualiza un registro
  public function actualizar()
  {
    // sanitizar datos
    $atributos = $this->sanitizarAtributos();
    $valores = [];

    foreach ($atributos as $key => $value) {
      $valores[] = "{$key}='{$value}'";
    }

    $query = " UPDATE propiedades SET ";
    $query .= join(', ', $valores);
    $query .= " WHERE id = '" . self::$db->escape_string($this->id) . "' ";
    $query .= " LIMIT 1 ";

    $resultado = self::$db->query($query);
    if ($resultado) {
      // Redireccionar al usuario.
      header('Location: /admin?resultado=2');
    }
  }

  //* obtener errores
  public static function getErrores()
  {
    return self::$errores;
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

  //* lista todas los registros
  public  static function all()
  {
    $query = "SELECT * FROM propiedades";
    $resultado = self::consultarSQL($query);
    return $resultado;
  }

  //* obtener un registro
  public static function find($id)
  {
    // Obtener los datos de la propiedad
    $query = "SELECT * FROM propiedades WHERE id = ${id}";
    $resultado = self::consultarSQL($query);
    return array_shift($resultado);
  }

  //* realiza la consulta a la base de datos y crea el arreglo que se le pasará a crearObjeto()
  public static function consultarSQL($query)
  {
    // consultar la base de datos
    $resultado = self::$db->query($query);

    // Iterar los resultados
    $array = [];
    while ($registro = $resultado->fetch_assoc()) :
      $array[] = self::crearObjeto($registro);
    endwhile;

    // liberar la memoria
    $resultado->free();

    //retornar los resultados
    return $array;
  }

  //* crea un objeto a partir de las keys y los values de un registro
  protected static function crearObjeto($registro)
  {
    $objeto = new self;
    foreach ($registro as $key => $value) {
      if (property_exists($objeto, $key)) {
        $objeto->$key = $value;
      }
    }
    return $objeto;
  }

  //* sincroniza objeto en memoria con los cambios realizados por el usuario
  public function sincronizar($args = [])
  {
    foreach ($args as $key => $value) :
      if (property_exists($this, $key) && !is_null($value)) {
        $this->$key = $value;
      }
    endforeach;
  }
}
