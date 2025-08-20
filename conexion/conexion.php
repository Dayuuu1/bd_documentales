<?php
require __DIR__ . '/../vendor/autoload.php';

use MongoDB\Client;
use MongoDB\Driver\Exception\Exception as MongoDBException;

try {
    $cliente = new Client("mongodb://localhost:27017");
    $bd = $cliente->RedSocial;

} catch (MongoDBException $e) {
    die("Error de conexión a MongoDB: " . $e->getMessage());
}

return $bd;
