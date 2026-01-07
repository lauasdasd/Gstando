<?php
// app/Controllers/LoginController.php

require_once __DIR__ . '/../Models/Usuario.php';

class LoginController {
    
    private $usuarioModel;

    public function __construct() {
        $this->usuarioModel = new Usuario();
    }

    public function mostrarFormulario() {
        require_once __DIR__ . '/../Views/login/login.php';
    }

public function login() {
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['nombre_usuario']) && isset($_POST['contrasena'])) {
        $nombre_usuario = $_POST['nombre_usuario'];
        $contrasena = $_POST['contrasena'];

        // --- Inicio de las líneas de depuración ---
        echo "Intentando iniciar sesión con:<br>";
        echo "Usuario: " . htmlspecialchars($nombre_usuario) . "<br>";
        echo "Contraseña: " . htmlspecialchars($contrasena) . "<br>";
        echo "<hr>";
        // --- Fin de las líneas de depuración ---

        $usuario = $this->usuarioModel->obtenerPorNombreUsuario($nombre_usuario);

        // --- Más líneas de depuración ---
        echo "Datos del usuario obtenidos de la DB:<br>";
        var_dump($usuario);
        echo "<hr>";
        // --- Fin de las líneas de depuración ---

        if ($usuario && password_verify($contrasena, $usuario['contrasena'])) {
            // Autenticación exitosa
            $_SESSION['usuario_id'] = $usuario['id'];
            $_SESSION['nombre_usuario'] = $usuario['nombre_usuario'];
            $_SESSION['rol'] = $usuario['rol'];
            
            // Si funciona, redirige. No pongas 'die' aquí.
            header('Location: ' . BASE_URL); 
            exit;
        } else {
            // Autenticación fallida
            $error = "Nombre de usuario o contraseña incorrectos.";
            // Agregamos un mensaje para saber si falla por aquí
            echo "Autenticación fallida. Revisa el usuario y contraseña.";
            die();
            require_once __DIR__ . '/../Views/login/login.php';
        }
    }
}

public function logout() {
    // 1. Iniciar la sesión (si no se hizo antes, aunque debería hacerse en index.php)
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    
    // 2. Destruir todas las variables de sesión
    $_SESSION = array(); 
    
    // 3. Destruir la cookie de sesión
    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }
    
    // 4. Destruir la sesión
    session_destroy();
    
    // 5. Redirigir al login o a la página principal
    header('Location: ' . BASE_URL . 'login');
    exit;
}
}