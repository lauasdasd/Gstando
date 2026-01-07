<?php
// app/Controllers/ClienteController.php

// Incluimos el modelo para poder usarlo
require_once __DIR__ . '/../Models/Cliente.php';
require_once __DIR__ . '/../Models/Prestamo.php';

class ClienteController {
    
    private $clienteModel;
    private $prestamoModel;

    public function __construct() {
        $this->clienteModel = new Cliente();
        $this->prestamoModel = new Prestamo();
    }

    public function listar() {
        // 1. Obtener los datos del modelo
        $clientes = $this->clienteModel->obtenerTodos();

        // 2. Cargar la vista y pasarle los datos
        require_once __DIR__ . '/../Views/clientes/listar.php';
    }

    public function crear() {
        // Solo mostramos la vista con el formulario para crear un cliente
        require_once __DIR__ . '/../Views/clientes/crear.php';
    }

    public function guardar() {
    // Verificamos que la petición sea POST
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        // Recopilamos todos los datos del formulario, incluyendo los nuevos campos
        $datos = [
            'nombre' => $_POST['nombre'] ?? '',
            'apellido' => $_POST['apellido'] ?? '',
            'fecha_nacimiento' => $_POST['fecha_nacimiento'] ?? '',
            'lugar_nacimiento' => $_POST['lugar_nacimiento'] ?? '',
            'nacionalidad' => $_POST['nacionalidad'] ?? '',
            'profesion' => $_POST['profesion'] ?? '',
            'dni' => $_POST['dni'] ?? '',
            'cuil' => $_POST['cuil'] ?? '',
            'telefono' => $_POST['telefono'] ?? '',
            'estado_civil' => $_POST['estado_civil'] ?? '',
            'sexo' => $_POST['sexo'] ?? '',
            'seguro' => $_POST['seguro'] ?? '',
            'email' => $_POST['email'] ?? '',
            'domicilio' => $_POST['domicilio'] ?? '',
            'localidad' => $_POST['localidad'] ?? '',
            'codigo_postal' => $_POST['codigo_postal'] ?? ''
        ];

        // Usamos el modelo para guardar el cliente
        if ($this->clienteModel->guardar($datos)) {
            header('Location: ' . BASE_URL . 'clientes?success=1');
            exit;
        } else {
            header('Location: ' . BASE_URL . 'clientes/crear?error=1');
            exit;
        }
    }
    
    // Si no es POST, redirigimos al formulario de creación
    header('Location: ' . BASE_URL . 'clientes/crear');
    exit;
}
public function perfil() {
    $id = isset($_GET['idCliente']) ? $_GET['idCliente'] : null;

    if ($id === null) {
        header('Location: ' . BASE_URL . 'clientes');
        exit;
    }
    
    // Instancia los modelos
    $clienteModel = new Cliente();
    $prestamoModel = new Prestamo(); // Asegúrate de que el modelo de Préstamo esté instanciado
    
    // Obtiene los datos del cliente
    $cliente = $clienteModel->obtenerPorId($id);

    // Si no se encuentra el cliente, redirige
    if (!$cliente) {
        header('Location: ' . BASE_URL . 'clientes');
        exit;
    }

    // Obtiene los préstamos de ese cliente
    $prestamos = $prestamoModel->obtenerPrestamosPorCliente($id);

    // Combina los datos del cliente y los préstamos en un solo array
    extract([
        'cliente' => $cliente,
        'prestamos' => $prestamos
    ]);

    // Carga la vista del perfil con todos los datos
    require_once __DIR__ . '/../Views/Clientes/perfil.php';
}

public function verModal() {
    $id = $_GET['idCliente'] ?? null;
    $cliente = $this->clienteModel->obtenerPorId($id);

    if ($cliente) {
        // En este caso, solo necesitas el body del modal, no el header ni el footer
        require_once __DIR__ . '/../Views/Clientes/modal_cliente.php';
    } else {
        echo '<p class="text-danger">Cliente no encontrado.</p>';
    }
}

}