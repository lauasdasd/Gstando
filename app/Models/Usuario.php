<?php
// app/Models/Usuario.php

class Usuario {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function obtenerPorNombreUsuario($nombre_usuario) {
        $sql = "SELECT * FROM usuarios WHERE nombre_usuario = ? LIMIT 1";
        $stmt = $this->db->getConnection()->prepare($sql);
        
        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $this->db->getConnection()->error);
        }
        
        $stmt->bind_param("s", $nombre_usuario);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            return $result->fetch_assoc();
        }
        
        return null;
    }
    public function obtenerTodos() {
        $sql = "SELECT id, nombre_usuario, nombre_completo, email, rol FROM usuarios ORDER BY nombre_usuario ASC";
        $result = $this->db->getConnection()->query($sql);
        
        $usuarios = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $usuarios[] = $row;
            }
        }
        return $usuarios;
    }
        // app/Models/Usuario.php

    public function obtenerPorId($id) {
        // Ejemplo de consulta (ajusta esto a tu base de datos)
        $sql = "SELECT id, nombre_usuario, nombre_completo, email, rol, contrasena FROM usuarios WHERE id = ?";
        
        // Asumo que tienes una forma de preparar y ejecutar consultas
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        
        return $result->fetch_assoc();
    }
        /**
     * Guarda un nuevo usuario en la base de datos.
     * @param array $datos Los datos del usuario a guardar.
     * @return bool Retorna true si se guardó correctamente, false en caso contrario.
     */
    public function guardar($datos) {
        $sql = "INSERT INTO usuarios (nombre_usuario, contrasena, nombre_completo, email, rol) VALUES (?, ?, ?, ?, ?)";
        $stmt = $this->db->getConnection()->prepare($sql);
        
        if ($stmt === false) {
            die('Error al preparar la consulta: ' . $this->db->getConnection()->error);
        }

        // Cifrar la contraseña antes de guardarla
        $contrasena_cifrada = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
        
        $stmt->bind_param(
            "sssss",
            $datos['nombre_usuario'],
            $contrasena_cifrada, // Usamos la contraseña cifrada
            $datos['nombre_completo'],
            $datos['email'],
            $datos['rol']
        );
        
        $result = $stmt->execute();
        $stmt->close();
        
        return $result;
    }
    // app/Models/Usuario.php

public function actualizar($id, $datos) {
    // 1. Iniciar la consulta SQL base
    $sql = "UPDATE usuarios SET nombre_usuario = ?, nombre_completo = ?, email = ?, rol = ?";
    $params = [$datos['nombre_usuario'], $datos['nombre_completo'], $datos['email'], $datos['rol']];
    $types = "ssss"; // Tipos de datos: string, string, string, string

    // 2. Añadir la contraseña si se proporcionó una nueva
    if (!empty($datos['contrasena'])) {
        $sql .= ", contrasena = ?";
        // ¡IMPORTANTE! NUNCA guardar la contraseña sin hashearla
        $hashed_password = password_hash($datos['contrasena'], PASSWORD_DEFAULT);
        
        $params[] = $hashed_password;
        $types .= "s";
    }

    // 3. Añadir la condición WHERE
    $sql .= " WHERE id = ?";
    $params[] = $id;
    $types .= "i"; // 'i' para entero (id)

    // 4. Ejecutar la consulta
    try {
        $stmt = $this->db->getConnection()->prepare($sql);
        // Usar call_user_func_array para bind_param dinámico
        $stmt->bind_param($types, ...$params);
        
        return $stmt->execute();
    } catch (\Exception $e) {
        // Manejar el error de la base de datos
        // log_error($e->getMessage()); 
        return false;
    }
}
}