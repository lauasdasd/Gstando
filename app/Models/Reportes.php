<?php
// app/Models/ReportesModel.php

class Reportes{
    private $db;

    public function __construct() {
        // Conexión a la base de datos a través de la clase Database
        $this->db = new Database();
    }

    /**
     * Obtiene los préstamos que finalizan en los próximos 30 días.
     * @return array
     */
    public function PrestamosPorFinalizarProximos30Dias() {
        $sql = "
            SELECT
                p.numero_solicitud,
                p.importe,
                p.fecha_finalizacion,
                c.nombre AS nombre_cliente,
                c.apellido AS apellido_cliente
            FROM
                prestamos AS p
            JOIN
                clientes AS c ON p.cliente_id = c.idCliente
            WHERE
                p.fecha_finalizacion BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
            ORDER BY
                p.fecha_finalizacion ASC;
        ";
        
        $result = $this->db->getConnection()->query($sql);
        
        $prestamos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prestamos[] = $row;
            }
        }
        return $prestamos;
    }

    /**
     * Obtiene los préstamos finalizados en los últimos 3 meses.
     * @return array
     */
    public function getPrestamosFinalizadosUltimos3Meses() {
        $sql = "
            SELECT
                p.numero_solicitud,
                p.importe,
                p.fecha_finalizacion,
                c.nombre AS nombre_cliente,
                c.apellido AS apellido_cliente
            FROM
                prestamos AS p
            JOIN
                clientes AS c ON p.cliente_id = c.idCliente
            WHERE
                p.estado = 'Finalizado'
                AND p.fecha_finalizacion BETWEEN DATE_SUB(CURDATE(), INTERVAL 3 MONTH) AND CURDATE()
            ORDER BY
                p.fecha_finalizacion DESC;
        ";

        $result = $this->db->getConnection()->query($sql);
        
        $prestamos = [];
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prestamos[] = $row;
            }
        }
        return $prestamos;
    }
    /**
 * Obtiene el conteo de préstamos por estado.
 * @return array
 */
public function getResumenPorEstado() {
    $sql = "
        SELECT 
            estado,
            COUNT(*) AS total
        FROM 
            prestamos
        GROUP BY 
            estado;
    ";
    $result = $this->db->getConnection()->query($sql);
    $data = [];
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $data[$row['estado']] = $row['total'];
        }
    }
    return $data;
}

/**
 * Obtiene el total de préstamos activos.
 * @return array
 */
public function getImporteTotalActivo() {
    $sql = "
        SELECT 
            SUM(importe) AS total
        FROM 
            prestamos
        WHERE
            estado = 'Activo';
    ";
    $result = $this->db->getConnection()->query($sql);
    $row = $result->fetch_assoc();
    return $row['total'];
}
/**
     * Obtiene todos los préstamos con el estado 'Activo'.
     * @return array
     */
    public function getPrestamosActivos() {
        $sql = "
            SELECT
                p.numero_solicitud,
                p.importe,
                p.fecha_finalizacion,
                c.nombre AS nombre_cliente,
                c.apellido AS apellido_cliente,
                p.estado
            FROM
                prestamos AS p
            JOIN
                clientes AS c ON p.cliente_id = c.idCliente
            WHERE
                p.estado = 'Activo'
            ORDER BY
                p.fecha_finalizacion DESC;
        ";
        
        $result = $this->db->getConnection()->query($sql);
        
        $prestamos = [];
        if ($result && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $prestamos[] = $row;
            }
        }
        return $prestamos;
    }
    // app/Models/Reportes.php

// ... (métodos existentes) ...

// --- Nuevos métodos reescritos para usar MySQLi ---

public function getTopLineasCredito($limit = 10) {
    $sql = "SELECT linea_credito, COUNT(*) AS total_prestamos 
            FROM prestamos 
            GROUP BY linea_credito 
            ORDER BY total_prestamos DESC 
            LIMIT ?";
    
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}

public function getPrestamosPorMes() {
    $sql = "SELECT DATE_FORMAT(fecha_solicitud, '%Y-%m') as mes, COUNT(*) as total_prestamos 
            FROM prestamos 
            GROUP BY mes 
            ORDER BY mes ASC";
    
    $result = $this->db->getConnection()->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

public function getTopReparticiones($limit = 10) {
    // La columna 'reparticion' está directamente en la tabla 'prestamos'.
    // No se necesita una unión (JOIN).
    $sql = "SELECT reparticion, COUNT(*) AS total_prestamos 
            FROM prestamos 
            GROUP BY reparticion 
            ORDER BY total_prestamos DESC 
            LIMIT ?";
    
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare($sql);
    if ($stmt) {
        $stmt->bind_param('i', $limit);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }
    return [];
}
/**
     * Obtiene los préstamos por rango de fechas, con filtros opcionales.
     * @param array $filtros
     * @return array
     */
    public function obtenerReportePorFechas($filtros) {
        $sql = "
            SELECT 
                p.numero_solicitud,
                p.importe,
                p.fecha_solicitud,
                p.estado,
                p.linea_credito,
                c.nombre AS nombre_cliente,
                c.apellido AS apellido_cliente,
                p.reparticion
            FROM prestamos AS p
            JOIN clientes AS c ON p.cliente_id = c.idCliente
            WHERE 1=1
        ";
        
        $params = [];
        $types = "";

        if (isset($filtros['filtro_fecha']) && $filtros['filtro_fecha'] !== 'all') {
            switch ($filtros['filtro_fecha']) {
                case 'last_month':
                    $sql .= " AND p.fecha_solicitud >= DATE_SUB(CURDATE(), INTERVAL 1 MONTH) ";
                    break;
                case 'last_year':
                    $sql .= " AND p.fecha_solicitud >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR) ";
                    break;
                case 'custom':
                    if (!empty($filtros['fecha_inicio'])) {
                        $sql .= " AND p.fecha_solicitud >= ? ";
                        $params[] = $filtros['fecha_inicio'];
                        $types .= "s";
                    }
                    if (!empty($filtros['fecha_fin'])) {
                        $sql .= " AND p.fecha_solicitud <= ? ";
                        $params[] = $filtros['fecha_fin'];
                        $types .= "s";
                    }
                    break;
            }
        }
        
        $sql .= " ORDER BY p.fecha_solicitud DESC";

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        
        if ($stmt && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        // Fallback para consultas sin parámetros (ej: 'all')
        if ($result = $conn->query($sql)) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    /**
     * Obtiene los préstamos filtrados por cliente.
     * @param array $filtros
     * @return array
     */
    public function obtenerReportePorCliente($filtros) {
        $sql = "
            SELECT 
                p.numero_solicitud,
                p.importe,
                p.fecha_solicitud,
                p.estado,
                p.linea_credito,
                c.nombre AS nombre_cliente,
                c.apellido AS apellido_cliente,
                p.reparticion
            FROM prestamos AS p
            JOIN clientes AS c ON p.cliente_id = c.idCliente
            WHERE 1=1
        ";
        
        $params = [];
        $types = "";

        if (isset($filtros['filtro_cliente']) && !empty($filtros['filtro_cliente'])) {
            $cliente = '%' . $filtros['filtro_cliente'] . '%';
            $sql .= " AND (c.nombre LIKE ? OR c.apellido LIKE ? OR c.idCliente = ?)";
            $params = [$cliente, $cliente, $filtros['filtro_cliente']];
            $types = "ssi";
        }
        
        $sql .= " ORDER BY c.apellido, c.nombre DESC";

        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        
        if ($stmt && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        // Fallback
        if ($result = $conn->query($sql)) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }

    /**
     * Obtiene los préstamos filtrados por línea de crédito.
     * @param array $filtros
     * @return array
     */
    public function obtenerReportePorLineaCredito($filtros) {
        $sql = "
            SELECT 
                p.numero_solicitud,
                p.importe,
                p.fecha_solicitud,
                p.estado,
                p.linea_credito,
                c.nombre AS nombre_cliente,
                c.apellido AS apellido_cliente,
                p.reparticion
            FROM prestamos AS p
            JOIN clientes AS c ON p.cliente_id = c.idCliente
            WHERE 1=1
        ";
        
        $params = [];
        $types = "";

        if (isset($filtros['filtro_linea_credito']) && !empty($filtros['filtro_linea_credito'])) {
            $linea_credito = '%' . $filtros['filtro_linea_credito'] . '%';
            $sql .= " AND p.linea_credito LIKE ?";
            $params[] = $linea_credito;
            $types .= "s";
        }
        
        $sql .= " ORDER BY p.fecha_solicitud DESC";
        
        $conn = $this->db->getConnection();
        $stmt = $conn->prepare($sql);
        
        if ($stmt && !empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        
        if ($stmt) {
            $stmt->execute();
            $result = $stmt->get_result();
            return $result->fetch_all(MYSQLI_ASSOC);
        }
        
        if ($result = $conn->query($sql)) {
            return $result->fetch_all(MYSQLI_ASSOC);
        }

        return [];
    }
    // app/Models/Reportes.php

public function obtenerLineasDeCreditoDisponibles() {
    // Ejemplo de cómo obtener una lista única
    $sql = "SELECT DISTINCT linea_credito FROM prestamos ORDER BY linea_credito ASC";
    $result = $this->db->getConnection()->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}

public function obtenerReparticionesDisponibles() {
    // Ejemplo de cómo obtener una lista única
    $sql = "SELECT DISTINCT reparticion FROM prestamos WHERE reparticion IS NOT NULL AND reparticion != '' ORDER BY reparticion ASC";
    $result = $this->db->getConnection()->query($sql);
    return $result ? $result->fetch_all(MYSQLI_ASSOC) : [];
}
    public function obtenerReportePorReparticion($filtros) {
    $sql = "
        SELECT 
            p.numero_solicitud,
            p.importe,
            p.fecha_solicitud,
            p.estado,
            p.linea_credito,
            p.reparticion,
            c.nombre AS nombre_cliente,
            c.apellido AS apellido_cliente
        FROM prestamos AS p
        JOIN clientes AS c ON p.cliente_id = c.idCliente
        WHERE 1=1
    ";
    
    $params = [];
    $types = "";

    // Lógica para el filtro de repartición
    if (isset($filtros['filtro_reparticion']) && !empty($filtros['filtro_reparticion'])) {
        // Asumiendo que el filtro puede ser parcial (LIKE)
        $reparticion = '%' . $filtros['filtro_reparticion'] . '%'; 
        $sql .= " AND p.reparticion LIKE ?";
        $params[] = $reparticion;
        $types .= "s";
    }
    
    $sql .= " ORDER BY p.fecha_solicitud DESC";
    
    $conn = $this->db->getConnection();
    $stmt = $conn->prepare($sql);
    
    // Si hay parámetros, los bindea
    if ($stmt && !empty($params)) {
        // Usar el operador de propagación (...) para pasar los parámetros
        $stmt->bind_param($types, ...$params);
    }
    
    if ($stmt) {
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Fallback para consultas sin parámetros (si el prepare con bind falla o no hay parámetros)
    if (empty($params) && $result = $conn->query($sql)) {
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    return [];
}
}