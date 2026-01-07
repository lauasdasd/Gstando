<?php
// app/Controllers/UsuarioController.php

require_once __DIR__ . '/../Models/Usuario.php';

class UsuarioController {
    
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function listar() {
        // Solo los administradores deberían poder ver esta lista
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL);
            exit;
        }

        $usuarios = $this->usuarioModel->obtenerTodos();
        require_once __DIR__ . '/../Views/usuarios/listar.php';
    }
    public function crear() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL);
            exit;
        }

        require_once __DIR__ . '/../Views/usuarios/crear.php';
    }
public function ver() {
    // 1. OBTENER el ID de la query string (GET)
    // Usamos el operador de fusión null '??' para seguridad
    $id = $_GET['id'] ?? null; 

    if (!$id || !is_numeric($id)) {
        header('Location: ' . BASE_URL . 'usuarios');
        exit;
    }

    // 2. Usar la instancia del modelo que ya está en el constructor
    $usuario = $this->usuarioModel->obtenerPorId((int)$id); 
    
    if (!$usuario) {
        header('Location: ' . BASE_URL . 'usuarios');
        exit;
    }
    
    $titulo = 'Perfil de Usuario';
    
    // 3. Incluir la vista
    require_once __DIR__ . '/../Views/usuarios/ver.php';
}
public function perfil() {
    // 1. Verificar si el usuario está logueado y obtener su ID
    if (!isset($_SESSION['id']) || empty($_SESSION['id'])) {
        header('Location: ' . BASE_URL . 'login'); // Redirige si no está logueado
        exit;
    }
    
    $id_usuario_logueado = $_SESSION['id'];
    
    // 2. Reutilizar el método 'ver' que ya funciona, pasándole el ID de la sesión.
    // El método ver() manejará la obtención de datos y la carga de la vista.
    $this->ver($id_usuario_logueado); 
}
// Y tu método actualizar también necesitaría leer el ID de $_POST o $_GET si se actualiza sin un ID en la ruta
public function actualizar() {
    $id = $_POST['id'] ?? null;
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            
            $datos = [
                'nombre_usuario'    => $_POST['nombre_usuario'] ?? '',
                'nombre_completo'   => $_POST['nombre_completo'] ?? '',
                'email'             => $_POST['email'] ?? '',
                'rol'               => $_POST['rol'] ?? '',
                'contrasena'        => $_POST['password'] ?? null // solo si el campo no está vacío
            ];

            // 1. Llamar al método de actualización del modelo
            // Necesitas crear el método 'actualizar' en tu modelo
            if ($this->usuarioModel->actualizar($id, $datos)) {
                // Éxito: redirigir a la misma página con mensaje de éxito
                header('Location: ' . BASE_URL . 'usuarios/ver/' . $id . '?update_success=1');
                exit;
            } else {
                // Error
                header('Location: ' . BASE_URL . 'usuarios/ver/' . $id . '?update_error=1');
                exit;
            }
        }
        // Si no es POST, redirigir al perfil para que lo vea
        header('Location: ' . BASE_URL . 'usuarios/ver/' . $id);
        exit;
    }

    public function guardar() {
        if (!isset($_SESSION['rol']) || $_SESSION['rol'] !== 'admin') {
            header('Location: ' . BASE_URL);
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_usuario'])) {
            $datos = [
                'nombre_usuario' => $_POST['nombre_usuario'],
                'contrasena' => $_POST['contrasena'],
                'nombre_completo' => $_POST['nombre_completo'],
                'email' => $_POST['email'],
                'rol' => $_POST['rol']
            ];

            if ($this->usuarioModel->guardar($datos)) {
                header('Location: ' . BASE_URL . 'usuarios?success=1');
                exit;
            } else {
                header('Location: ' . BASE_URL . 'usuarios/crear?error=1');
                exit;
            }
        }
        header('Location: ' . BASE_URL . 'usuarios/crear');
        exit;
    }
}