<?php
// public/index.php

session_start();
date_default_timezone_set('America/Argentina/Buenos_Aires');

// =========================================================================
// Cargar archivos y dependencias
// =========================================================================

require_once __DIR__ . '/../app/Config/global.php'; // Carga la constante BASE_URL
require_once __DIR__ . '/../core/Router.php';
require_once __DIR__ . '/../core/Database.php';

// Cargar todos los controladores para que el autoloader los encuentre
require_once __DIR__ . '/../app/Controllers/HomeController.php';
require_once __DIR__ . '/../app/Controllers/LoginController.php';
require_once __DIR__ . '/../app/Controllers/ClienteController.php';
require_once __DIR__ . '/../app/Controllers/PrestamoController.php';
require_once __DIR__ . '/../app/Controllers/UsuarioController.php';

$router = new Router();

// =========================================================================
// RUTAS
// =========================================================================

// Rutas de Login y Logout (siempre accesibles)
$router->addRoute('GET', '/login', 'LoginController@mostrarFormulario');
$router->addRoute('POST', '/login', 'LoginController@login');
$router->addRoute('GET', '/logout', 'LoginController@logout');

// Rutas protegidas (solo accesibles si hay una sesión activa)
if (isset($_SESSION['usuario_id'])) {
    $router->addRoute('GET', '/', 'HomeController@index');
    $router->addRoute('GET', '/home/otros-reportes', 'ReportesController@generar');

    
    // Rutas de clientes
    $router->addRoute('GET', '/clientes', 'ClienteController@listar');
    $router->addRoute('GET', '/clientes/crear', 'ClienteController@crear');
    $router->addRoute('POST', '/clientes/guardar', 'ClienteController@guardar');
    $router->addRoute('GET', '/clientes/perfil', 'ClienteController@perfil');
    $router->addRoute('GET', '/clientes/modal_cliente', 'ClienteController@verModal');
    // Rutas de préstamos
    $router->addRoute('GET', '/prestamos', 'PrestamoController@listar');
    $router->addRoute('GET', '/prestamos/data', 'PrestamoController@listadoAjax');
    $router->addRoute('GET', '/prestamos/crear', 'PrestamoController@crear');
    $router->addRoute('GET', '/prestamos/ver', 'PrestamoController@ver');
    $router->addRoute('POST', '/prestamos/guardar', 'PrestamoController@guardar');
    $router->addRoute('POST', '/prestamos/actualizarEstado', 'PrestamoController@actualizarEstado');
    $router->addRoute('GET', '/prestamos/reporte-diario', 'PrestamoController@reporteDiario');
    $router->addRoute('GET', '/prestamos/imprimir-reporte-diario', 'PrestamoController@imprimirReporteDiario');
    $router->addRoute('GET', '/prestamos/caratula', 'PrestamoController@imprimirCaratula');

    // Rutas de Usuarios
    $router->addRoute('GET', '/usuarios', 'UsuarioController@listar');
    $router->addRoute('GET', '/usuarios/crear', 'UsuarioController@crear');
    $router->addRoute('POST', '/usuarios/guardar', 'UsuarioController@guardar');
    // Rutas Nuevas para Ver y Actualizar (CON PARÁMETRO {id})
    $router->addRoute('GET', '/usuarios/ver', 'UsuarioController@ver'); 
    $router->addRoute('POST', '/usuarios/actualizar', 'UsuarioController@actualizar');
    $router->addRoute('GET', '/perfil', 'UsuarioController@perfil');
    // Rutas para el nuevo módulo de consultas
    $router->addRoute('GET', '/consultas', 'ConsultaController@index');
    $router->addRoute('POST', '/consultas/procesar', 'ConsultaController@procesar');
    $router->addRoute('POST', '/consultas/guardar', 'ConsultaController@guardar');
    $router->addRoute('GET', '/consultas/generarReporte', 'ConsultaController@generarReporte');


}




// =========================================================================
// DESPACHO DE LA RUTA
// =========================================================================

$requestMethod = $_SERVER['REQUEST_METHOD'];

// Lógica para obtener la URI, compatible con tu .htaccess
if (isset($_GET['url'])) {
    $requestUri = $_GET['url'];
} else {
    $requestUri = str_replace(BASE_URL, '', $_SERVER['REQUEST_URI']);
    $requestUri = trim($requestUri, '/');
}

// Redirigimos si no hay sesión y la ruta no es de login
if (!isset($_SESSION['usuario_id']) && $requestUri !== 'login') {
    header('Location: ' . BASE_URL . 'login');
    exit;
}

$router->dispatch($requestMethod, $requestUri);