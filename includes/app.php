<?php

require 'funciones.php';
require 'config/database.php';
require __DIR__ . '/../vendor/autoload.php';


use App\Propiedad;

$propiedad = new Propiedad;

echo '<pre>';
var_dump($propiedad);
echo '</pre>';
