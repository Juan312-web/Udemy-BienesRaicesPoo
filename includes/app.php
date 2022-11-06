<?php
require 'config/database.php';
require 'funciones.php';
require __DIR__ . '/../vendor/autoload.php';

// conectar a la base de datps
$db = conectarDB();

use App\ActiveRecord;

ActiveRecord::setDB($db);
