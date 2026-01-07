<?php
// app/Controllers/PrestamoController.php

require_once __DIR__ . '/../Models/Prestamo.php';
require_once __DIR__ . '/../Models/Cliente.php'; // Necesitamos el modelo de Clientes para el formulario
require_once __DIR__ . '/../Models/Usuario.php';

class PrestamoController {
    private $prestamoModel;
    private $clienteModel;
    private $usuarioModel;

    public function __construct() {
        $this->prestamoModel = new Prestamo();
        $this->clienteModel = new Cliente();
        $this->usuarioModel = new Usuario();
    }

// En app/Controllers/PrestamoController.php

public function listar() {
    // Solo obtenemos los datos que la vista necesita.
    // En este caso, la lista de usuarios para llenar el filtro <select>.
    $usuarios = $this->usuarioModel->obtenerTodos(); 
    $prestamos = $this->prestamoModel->obtenerTodos(); 
    
    // Y luego, simplemente cargamos la vista.
    require_once __DIR__ . '/../Views/prestamos/listar.php';
}
public function listadoAjax() {
    // Obtenemos los datos de los préstamos
    $prestamos = $this->prestamoModel->obtenerTodos(); 

    // Preparamos la respuesta JSON
    header('Content-Type: application/json');
    echo json_encode(['data' => $prestamos]);
    exit; // Terminamos la ejecución para evitar que se cargue cualquier otra cosa
}
    public function crear() {
        // Obtenemos la lista de clientes para rellenar el select del formulario
        $clientes = $this->clienteModel->obtenerTodos();
        require_once __DIR__ . '/../Views/prestamos/crear.php';
    }

public function guardar() {
    require_once __DIR__ . '/../Helpers/NumberToWords.php';
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['cliente_id'])) {
        $importe = $_POST['importe'] ?? 0.00;
        
        $datos = [
            'cliente_id' => $_POST['cliente_id'] ?? null,
            'usuario_id' => $_SESSION['usuario_id'],
            'numero_solicitud' => $_POST['numero_solicitud'] ?? '',
            'importe' => $importe,
            'importe_letras' => numeroALetras($importe),
            'caja_ahorro' => $_POST['caja_ahorro'] ?? '',
            'fecha_solicitud' => $_POST['fecha_solicitud'] ?? date('Y-m-d'),
            'fecha_inicio' => $_POST['fecha_inicio'] ?? date('Y-m-d'),
            'fecha_finalizacion' => $_POST['fecha_finalizacion'] ?? date('Y-m-d'),
            'numero_cuotas' => $_POST['numero_cuotas'] ?? 1,
            'linea_credito' => $_POST['linea_credito'] ?? '',
            'reparticion' => $_POST['reparticion'] ?? '',
            'destino_prestamo' => $_POST['destino_prestamo'] ?? '',
            'estado' => 'Activo',
            'observaciones' => $_POST['observaciones'] ?? '',
            'estado_banco' => $_POST['estado_banco'] ?? 'Pendiente'
        ];

        $resultado = $this->prestamoModel->guardar($datos);

        if ($resultado === true) {
            header('Location: ' . BASE_URL . 'prestamos?success=1');
            exit;
        } elseif ($resultado === "duplicate_entry") {
            header('Location: ' . BASE_URL . 'prestamos/crear?error=duplicate');
            exit;
        } else {
            header('Location: ' . BASE_URL . 'prestamos/crear?error=1');
            exit;
        }
    }
    header('Location: ' . BASE_URL . 'prestamos/crear');
    exit;
}
    public function reporteDiario() {
        // Verificar que haya una sesión de usuario activa
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];
        
        // Obtener la fecha del filtro, o usar la fecha de hoy por defecto
        $fechaFiltro = isset($_GET['fecha']) && !empty($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');
        
        $prestamos = $this->prestamoModel->obtenerPrestamosPorDiaYUsuario($usuarioId, $fechaFiltro);

        // Pasamos la fecha del filtro a la vista
        require_once __DIR__ . '/../Views/prestamos/reporte_diario.php';
    }
    public function imprimirReporteDiario() {
        if (!isset($_SESSION['usuario_id'])) {
            header('Location: ' . BASE_URL . 'login');
            exit;
        }

        $usuarioId = $_SESSION['usuario_id'];
        $fechaFiltro = isset($_GET['fecha']) && !empty($_GET['fecha']) ? $_GET['fecha'] : date('Y-m-d');

        $prestamos = $this->prestamoModel->obtenerPrestamosPorDiaYUsuario($usuarioId, $fechaFiltro);

        require_once __DIR__ . '/../Views/prestamos/imprimir_reporte_diario.php';
    }
    public function actualizarEstado() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['prestamo_id'])) {
        $prestamoId = $_POST['prestamo_id'];
        $estadoBanco = $_POST['estado_banco'];

        if ($this->prestamoModel->actualizarEstadoBanco($prestamoId, $estadoBanco)) {
            header('Location: ' . BASE_URL . 'prestamos?success=1');
            exit;
        } else {
            header('Location: ' . BASE_URL . 'prestamos?error=1');
            exit;
        }
    }
    header('Location: ' . BASE_URL . 'prestamos');
    exit;
}
public function ver() {
    $prestamoId = $_GET['id'] ?? null;
    
    // Si no se proporciona un ID, redirige
    if (!$prestamoId) {
        header('Location: ' . BASE_URL . 'prestamos?error=notfound');
        exit;
    }
    
    // Carga el préstamo con el ID específico
    $prestamo = $this->prestamoModel->getPrestamoById($prestamoId);

    // Si el préstamo no existe, redirige
    if (!$prestamo) {
        header('Location: ' . BASE_URL . 'prestamos?error=notfound');
        exit;
    }

    require_once __DIR__ . '/../Views/Prestamos/ver.php';
}

public function imprimirCaratula() {
    $id = $_GET['id'] ?? null;

    if (!$id) {
        header('Location: ' . BASE_URL . 'prestamos?error=notfound');
        exit;
    }

    $prestamo = $this->prestamoModel->getPrestamoById($id);

    if (!$prestamo) {
        header('Location: ' . BASE_URL . 'prestamos?error=notfound');
        exit;
    }
    
    require_once __DIR__ . '/../Views/Prestamos/caratula.php';
}
}