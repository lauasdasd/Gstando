<?php
// app/Controllers/HomeController.php

require_once __DIR__ . '/../Models/Reportes.php';
require_once __DIR__ . '/../Models/Prestamo.php';
require_once __DIR__ . '/../Models/Cliente.php';

class HomeController {
    
    private $reportesModel;
    private $prestamoModel;
    private $clienteModel;

    public function __construct() {
        $this->reportesModel = new Reportes();
        $this->prestamoModel = new Prestamo();
        $this->clienteModel = new Cliente();
    }

    public function index() {
        // Obtener los datos para los reportes predeterminados
        $prestamosPorFinalizar = $this->reportesModel->PrestamosPorFinalizarProximos30Dias();
        $prestamosFinalizados = $this->reportesModel->getPrestamosFinalizadosUltimos3Meses();
        $resumen_por_estado = $this->reportesModel->getResumenPorEstado();
        $prestamos_activos = $this->reportesModel->getPrestamosActivos();

        // Inicializamos las variables del reporte personalizado
        $resultados_reporte_personalizado = [];
        $titulo_reporte_personalizado = "";

        // Lógica para procesar los filtros de "Otros Reportes"
        if (isset($_GET['reporte_tipo']) && !empty($_GET['reporte_tipo'])) {
            $reporte_tipo = $_GET['reporte_tipo'];
            $filtros = $_GET;
            
            switch ($reporte_tipo) {
                case 'fechas':
                    $titulo_reporte_personalizado = "Reporte por Rango de Fechas";
                    $resultados_reporte_personalizado = $this->reportesModel->obtenerReportePorFechas($filtros);
                    break;
                case 'cliente':
                    $titulo_reporte_personalizado = "Reporte por Cliente";
                    $resultados_reporte_personalizado = $this->reportesModel->obtenerReportePorCliente($filtros);
                    break;
                case 'estado':
                    $titulo_reporte_personalizado = "Reporte por Estado";
                    $resultados_reporte_personalizado = $this->reportesModel->obtenerReportePorEstado($filtros);
                    break;
                case 'linea_credito':
                    $titulo_reporte_personalizado = "Reporte por Línea de Crédito";
                    $resultados_reporte_personalizado = $this->reportesModel->obtenerReportePorLineaCredito($filtros);
                    break;
            }
        }
        
        // Preparamos los datos para la vista
        $data = [
            'prestamos_por_finalizar' => $prestamosPorFinalizar,
            'prestamos_finalizados' => $prestamosFinalizados,
            'prestamos_activos' => $prestamos_activos,
            'resumen_por_estado' => $resumen_por_estado,
            'resultados_reporte_personalizado' => $resultados_reporte_personalizado,
            'titulo_reporte_personalizado' => $titulo_reporte_personalizado,
        ];
        
        // Cargar y mostrar la vista (ajusta esto a tu sistema de plantillas)
        // Usando una función auxiliar para la vista
        $this->view('home/index', $data);
    }
    
    private function view($viewName, $data = []) {
        extract($data);
        include __DIR__ . '/../Views/' . $viewName . '.php';
    }
}