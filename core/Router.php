<?php
// core/Router.php

class Router {
    private $routes = [];

    /**
     * Agrega una nueva ruta a la lista de rutas.
     * @param string $method El método HTTP (GET, POST, etc.)
     * @param string $uri La URI a la que responde la ruta
     * @param string $action La acción a ejecutar (ej. 'Controller@method')
     */
    public function addRoute($method, $uri, $action) {
        // Almacena la ruta, normalizando la URI para que sea consistente
        $this->routes[] = [
            'method' => $method,
            'uri' => $this->normalizeUri($uri),
            'action' => $action
        ];
    }

    /**
     * Procesa la petición actual y ejecuta la acción correspondiente.
     * @param string $requestMethod El método de la petición
     * @param string $requestUri La URI de la petición
     */
    public function dispatch($requestMethod, $requestUri) {
        // 1. Eliminar el query string de la URI
        $requestUri = strtok($requestUri, '?');

// ... el resto del código ...  
        // 2. Normaliza la URI de la petición para que coincida con las rutas
        $requestUri = $this->normalizeUri($requestUri);

        // Busca una ruta que coincida con el método y la URI
        foreach ($this->routes as $route) {
            if ($route['method'] === $requestMethod && $route['uri'] === $requestUri) {
                // Si la ruta se encuentra, ejecuta la acción
                return $this->callAction($route['action']);
            }
        }
        // Si no se encuentra ninguna ruta, muestra un error 404
        http_response_code(404);
        echo "<h1>Error 404 - Página no encontrada</h1>";
    }

    /**
     * Ejecuta el método del controlador especificado.
     * @param string $action La cadena 'Controlador@metodo'
     */
    private function callAction($action) {
        // Separa el nombre del controlador y del método
        list($controller, $method) = explode('@', $action);

        // Prepara la ruta completa al archivo del controlador
        $controllerFile = __DIR__ . "/../app/Controllers/{$controller}.php";

        // Verifica si el archivo del controlador existe
        if (!file_exists($controllerFile)) {
            die("El controlador {$controller} no existe.");
        }

        // Carga el archivo del controlador y lo instancia
        require_once $controllerFile;
        $controllerInstance = new $controller();

        // Verifica si el método existe en el controlador
        if (!method_exists($controllerInstance, $method)) {
            die("El método {$method} no existe en el controlador {$controller}.");
        }

        // Llama al método del controlador
        call_user_func([$controllerInstance, $method]);
    }

    /**
     * Normaliza una URI eliminando la barra inicial y el nombre de la carpeta base.
     * @param string $uri La URI a normalizar
     */
    private function normalizeUri($uri) {
        $uri = trim($uri, '/');
        $basePath = trim(BASE_URL, '/');

        if ($basePath !== '' && strpos($uri, $basePath) === 0) {
            $uri = substr($uri, strlen($basePath));
            $uri = trim($uri, '/');
        }

        return '/' . $uri;
    }
}