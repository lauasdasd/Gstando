<?php
// app/Models/Cliente.php

class Cliente {
    private $db;

    public function __construct() {
        // Conexión a la base de datos a través de la clase Database
        $this->db = new Database();
    }

    /**
     * Obtiene todos los clientes de la base de datos.
     * @return array Un array de objetos con los datos de los clientes.
     */
    public function obtenerTodos() {
        $sql = "SELECT * FROM clientes ORDER BY apellido, nombre";
        $result = $this->db->getConnection()->query($sql);
        
        $clientes = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $clientes[] = $row;
            }
        }
        return $clientes;
    }

    /**
     * Guarda un nuevo cliente en la base de datos.
     * @param array $datos Los datos del cliente a guardar.
     * @return bool Retorna true si se guardó correctamente, false en caso contrario.
     */
public function guardar($datos) {
    // Sentencia SQL con todos los marcadores de posición para las nuevas columnas
    $sql = "INSERT INTO clientes (
        nombre, apellido, fecha_nacimiento, lugar_nacimiento, nacionalidad,
        profesion, dni, cuil, telefono, estado_civil, sexo, seguro,
        email, domicilio, localidad, codigo_postal
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    // Preparamos la sentencia
    $stmt = $this->db->getConnection()->prepare($sql);
    
    if ($stmt === false) {
        die('Error al preparar la consulta: ' . $this->db->getConnection()->error);
    }
    
    // Vinculamos los parámetros con los datos
    // La cadena de tipos debe tener una 's' (string) por cada campo
    $stmt->bind_param(
        "ssssssssssssssss",
        $datos['nombre'],
        $datos['apellido'],
        $datos['fecha_nacimiento'],
        $datos['lugar_nacimiento'],
        $datos['nacionalidad'],
        $datos['profesion'],
        $datos['dni'],
        $datos['cuil'],
        $datos['telefono'],
        $datos['estado_civil'],
        $datos['sexo'],
        $datos['seguro'],
        $datos['email'],
        $datos['domicilio'],
        $datos['localidad'],
        $datos['codigo_postal']
    );
    
    // Ejecutamos la sentencia
    $result = $stmt->execute();
    
    // Cerramos el statement
    $stmt->close();
    
    return $result;
}
    public function obtenerPorId($id) {
        $sql = "SELECT * FROM clientes WHERE idCliente = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $cliente = $result->fetch_assoc();
        $stmt->close();
        return $cliente;
    }

    /**
     * Busca un cliente por su CUIL o DNI.
     * @param string $cuilDni El número de CUIL o DNI a buscar.
     * @return array|null El array asociativo del cliente encontrado o null si no existe.
     */
public function buscarPorCuilDni($cuilDni) {
    // Correcto: Llamar a prepare() a través del objeto de conexión que devuelve getConnection()
    $sql = "SELECT * FROM clientes WHERE cuil = ? OR dni = ? LIMIT 1";
    $stmt = $this->db->getConnection()->prepare($sql);
    
    if ($stmt === false) {
        // Manejo de error
        return null;
    }
    
    $stmt->bind_param("ss", $cuilDni, $cuilDni);
    $stmt->execute();
    $resultado = $stmt->get_result();
    
    if ($resultado->num_rows > 0) {
        return $resultado->fetch_assoc();
    } else {
        return null;
    }
}
}
