<?php
// app/Controllers/ReportesController.php

require_once __DIR__ . '/../Models/Reportes.php';
// Asegúrate de incluir cualquier otro modelo necesario, como Clientes y Prestamos
// require_once __DIR__ . '/../Models/Cliente.php';
// require_once __DIR__ . '/../Models/Prestamo.php';

class ReportesController {
    
    private $reportesModel;

    public function __construct() {
        $this->reportesModel = new Reportes();
    }

    public function generar() {
        // Inicializar todas las variables que la vista necesita
        $resultados_reporte_personalizado = [];
        $titulo_reporte_personalizado = "";
        $top_lineas_credito = [];
        $prestamos_por_mes = [];
        $top_reparticiones = [];

        // Obtener los datos analíticos para los gráficos
        // Estos datos se obtienen siempre para que los gráficos puedan renderizarse si es necesario
        $top_lineas_credito = $this->reportesModel->getTopLineasCredito();
        $prestamos_por_mes = $this->reportesModel->getPrestamosPorMes();
        $top_reparticiones = $this->reportesModel->getTopReparticiones();

        // Procesar los filtros si se envió el formulario
        if (isset($_GET['reporte_tipo']) && !empty($_GET['reporte_tipo'])) {
            $reporte_tipo = $_GET['reporte_tipo'];
            $filtros = $_GET;
            
            // Lógica para obtener el reporte personalizado...
            switch ($reporte_tipo) {
                case 'fechas':
                    $titulo_reporte_personalizado = "Reporte por Rango de Fechas";
                    $resultados_reporte_personalizado = $this->reportesModel->obtenerReportePorFechas($filtros);
                    break;
                case 'cliente':
                    $titulo_reporte_personalizado = "Reporte por Cliente";
                    $resultados_reporte_personalizado = $this->reportesModel->obtenerReportePorCliente($filtros);
                    break;
                case 'linea_credito':
                    $titulo_reporte_personalizado = "Reporte por Línea de Crédito";
                    // CORRECCIÓN: Llamar al método correcto para la tabla
                    $resultados_reporte_personalizado = $this->reportesModel->obtenerReportePorLineaCredito($filtros);
                    break;
                case 'reparticiones':
                    $titulo_reporte_personalizado = "Reporte por Reparticiones";
                    // AÑADIDO: Llama a la nueva función de reporte detallado
                    $resultados_reporte_personalizado = $this->reportesModel->obtenerReportePorReparticion($filtros);
                    break;
            }
        }
        
        // Preparar los datos para la vista
        $data = [
            'resultados_reporte_personalizado' => $resultados_reporte_personalizado,
            'titulo_reporte_personalizado' => $titulo_reporte_personalizado,
            'top_lineas_credito' => $top_lineas_credito,
            'prestamos_por_mes' => $prestamos_por_mes,
            'top_reparticiones' => $top_reparticiones,
            'lineas_credito_disponibles' => $this->reportesModel->obtenerLineasDeCreditoDisponibles(),
            'reparticiones_disponibles' => $this->reportesModel->obtenerReparticionesDisponibles(), // ¡DEBES CREAR ESTE MÉTODO!

        ];
        
        // Cargar y mostrar la vista
        $this->view('home/otros-reportes', $data);
    }
    
    private function view($viewName, $data = []) {
        extract($data);
        include __DIR__ . '/../Views/' . $viewName . '.php';
    }
}