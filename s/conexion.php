<?php
require __DIR__ . '/vendor/autoload.php'; 

use MongoDB\Client;

class Conexion {
    public static function conectar() {
        try {

            $cliente = new Client("mongodb://localhost:27017");
            $db = $cliente->Ceinfo;

            return $db;
        } catch (Exception $e) {
            die("Error al conectar con MongoDB: " . $e->getMessage());
        }
    }
}
