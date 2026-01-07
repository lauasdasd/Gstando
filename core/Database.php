<?php
// core/Database.php

class Database {
    private $connection;

    public function __construct() {
        // Obtenemos las credenciales desde el archivo de configuración global
        $this->connection = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

        // Verificamos si la conexión fue exitosa
        if ($this->connection->connect_error) {
            die("Error de conexión a la base de datos: " . $this->connection->connect_error);
        }
        
        // Configuramos el conjunto de caracteres a UTF-8 para evitar problemas de acentos y caracteres especiales
        $this->connection->set_charset("utf8mb4");
    }

    /**
     * Devuelve el objeto de conexión a la base de datos.
     * @return mysqli
     */
    public function getConnection() {
        return $this->connection;
    }

    /**
     * Cierra la conexión a la base de datos cuando el objeto es destruido.
     */
    public function __destruct() {
        if ($this->connection) {
            $this->connection->close();
        }
    }
}