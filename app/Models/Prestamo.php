<?php
// app/Models/Prestamo.php

class Prestamo {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Obtiene todos los préstamos de la base de datos.
     * @return array Un array de objetos con los datos de los préstamos.
     */
    public function obtenerTodos() {
        $sql = "SELECT p.*, CONCAT(c.nombre, ' ', c.apellido) AS cliente_nombre, u.nombre_completo as 'atendio'
FROM prestamos p 
JOIN clientes c ON p.cliente_id = c.idCliente
Join usuarios u on u.id = p.usuario_id
ORDER BY p.id DESC";
        $result = $this->db->getConnection()->query($sql);

        $prestamos = [];
        if ($result) {
            while ($row = $result->fetch_assoc()) {
                $prestamos[] = $row;
            }
        }
        return $prestamos;
    }

 /**
     * Guarda un nuevo préstamo en la base de datos.
     * @param array $datos Los datos del préstamo a guardar.
     * @return bool Retorna true si se guardó correctamente, false en caso contrario.
     */
    public function guardar($datos) {
        $sql = "INSERT INTO prestamos (
            cliente_id, usuario_id, numero_solicitud, importe, importe_letras,
            fecha_solicitud, fecha_inicio, fecha_finalizacion, numero_cuotas,
            estado, observaciones, estado_banco, linea_credito, reparticion, destino_prestamo, caja_ahorro
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        
        try {
            $stmt = $this->db->getConnection()->prepare($sql);

            $stmt->bind_param(
                "iisdssssisssssss", // Agregamos un 's' para el nuevo campo
                $datos['cliente_id'],
                $datos['usuario_id'],
                $datos['numero_solicitud'],
                $datos['importe'],
                $datos['importe_letras'],
                $datos['fecha_solicitud'],
                $datos['fecha_inicio'],
                $datos['fecha_finalizacion'],
                $datos['numero_cuotas'],
                $datos['estado'],
                $datos['observaciones'],
                $datos['estado_banco'],
                $datos['linea_credito'],
                $datos['reparticion'],
                $datos['destino_prestamo'],
                $datos['caja_ahorro'] // Agregamos el nuevo campo
            );
            $result = $stmt->execute();
            
            if ($result) {
                $stmt->close();
                return true;
            }
            
        } catch (mysqli_sql_exception $e) {
            if ($e->getCode() == 1062) {
                return "duplicate_entry";
            }
        }
        
        return false;
    }
    /**
     * Genera las cuotas de un préstamo y las guarda en la tabla de cuotas.
     */
    public function generarCuotas($prestamoId, $datos) {
        $sql = "INSERT INTO cuotas (prestamo_id, numero_cuota, fecha_vencimiento, importe, estado) VALUES (?, ?, ?, ?, 'Pendiente')";
        $stmt = $this->db->getConnection()->prepare($sql);
        
        if ($stmt === false) {
            die('Error al preparar la consulta de cuotas: ' . $this->db->getConnection()->error);
        }

        $importeTotal = $datos['importe'];
        $numeroCuotas = $datos['numero_cuotas'];
        $importeCuota = $importeTotal / $numeroCuotas;
        
        $fechaInicio = new DateTime($datos['fecha_inicio']);

        for ($i = 1; $i <= $numeroCuotas; $i++) {
            $fechaVencimiento = clone $fechaInicio;
            $fechaVencimiento->modify("+$i months"); // Vencimiento cada mes

            $stmt->bind_param(
                "iisd",
                $prestamoId,
                $i,
                $fechaVencimiento->format('Y-m-d'),
                $importeCuota
            );
            $stmt->execute();
        }
        $stmt->close();
    }
    /**
     * Obtiene los préstamos que están próximos a finalizar.
     * @param int $dias El número de días para la alerta.
     * @return array Un array de préstamos.
     */
    // app/Models/Prestamo.php

    public function obtenerPrestamosPorFinalizar($dias) {
        $sql = "SELECT p.*, c.idCliente AS cliente_id, CONCAT(c.nombre, ' ', c.apellido) AS cliente_nombre 
                FROM prestamos p 
                JOIN clientes c ON p.cliente_id = c.idCliente
                WHERE p.estado = 'Activo' 
                AND p.fecha_finalizacion <= DATE_ADD(CURDATE(), INTERVAL ? DAY)";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("i", $dias);
        $stmt->execute();
        $result = $stmt->get_result();

        $prestamos = [];
        while ($row = $result->fetch_assoc()) {
            $prestamos[] = $row;
        }
        $stmt->close();
        return $prestamos;
    }
    /**
     * Obtiene los préstamos creados en una fecha específica por un usuario.
     * @param int $usuarioId El ID del usuario que creó los préstamos.
     * @param string $fecha La fecha para buscar los préstamos (por defecto, la fecha actual).
     * @return array Un array de préstamos.
     */
    public function obtenerPrestamosPorDiaYUsuario($usuarioId, $fecha = null) {
    if ($fecha === null) {
        $fecha = date('Y-m-d');
    }

    $sql = "SELECT p.id, p.numero_solicitud, p.importe, p.fecha_solicitud, p.importe_letras,
                p.fecha_inicio, p.fecha_finalizacion, p.reparticion, p.destino_prestamo,
                CONCAT(cl.nombre, ' ', cl.apellido) AS cliente_nombre,
                u.nombre_completo AS usuario_nombre
            FROM prestamos p
            JOIN clientes cl ON p.cliente_id = cl.idCliente
            JOIN usuarios u ON p.usuario_id = u.id
            WHERE DATE(p.fecha_solicitud) = ? AND p.usuario_id = ?
            ORDER BY p.id ASC";
    
    $stmt = $this->db->getConnection()->prepare($sql);
    $stmt->bind_param("si", $fecha, $usuarioId);
    $stmt->execute();
    $result = $stmt->get_result();

    $prestamos = [];
    while ($row = $result->fetch_assoc()) {
        $prestamos[] = $row;
    }
    $stmt->close();
    return $prestamos;
}
    public function actualizarEstadoBanco($id, $estado_banco) {
        $sql = "UPDATE prestamos SET estado_banco = ? WHERE id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("si", $estado_banco, $id);
        $result = $stmt->execute();
        $stmt->close();
        return $result;
    }
    public function obtenerPrestamosPorCliente($clienteId) {
        $sql = "SELECT p.*, CONCAT(cl.nombre, ' ', cl.apellido) AS cliente_nombre
                FROM prestamos p
                JOIN clientes cl ON p.cliente_id = cl.idCliente
                WHERE p.cliente_id = ?
                ORDER BY p.fecha_inicio DESC";

        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("i", $clienteId);
        $stmt->execute();
        $result = $stmt->get_result();

        $prestamos = [];
        while ($row = $result->fetch_assoc()) {
            $prestamos[] = $row;
        }
        $stmt->close();
        return $prestamos;
    }
    public function getPrestamoById($id) {
        $sql = "SELECT p.*, c.*, u.nombre_completo FROM prestamos p 
        JOIN clientes c ON p.cliente_id = c.idCliente 
        JOIN usuarios u ON p.usuario_id = u.id
        WHERE p.id = ?";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        $prestamo = $result->fetch_assoc();
        $stmt->close();
        return $prestamo;
    }

}