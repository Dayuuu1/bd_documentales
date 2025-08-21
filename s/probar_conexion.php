<?php
require 'conexion.php';

$db = Conexion::conectar();

echo "✅ Conexión exitosa a MongoDB.<br>";
echo "Base de datos seleccionada: " . $db->getDatabaseName();
