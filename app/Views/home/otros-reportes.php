<?php require_once __DIR__ . '/../templates/header.php'; ?>
<style><style>
/* Estilo para las celdas de encabezado dentro de la tabla #reporteTabla */
#reporteTabla.dataTable thead.bg-custom th {
    /* Define tu color de fondo preferido, ej: Azul oscuro */
    background-color: #212529 !important; 
    /* Define el color del texto */
    color: white !important;
}

/* Opcional: Esto asegura que el borde inferior se vea bien */
#reporteTabla thead.bg-custom {
    border-bottom: 3px solid #007bff;
}
</style></style>
<div class="container my-5">
    <div class="report-container">
        <h3 class="mb-3 d-flex align-items-center"><i class="fas fa-filter me-2"></i> Generar Reporte Personalizado</h3>
        <form id="reporteForm" action="<?= BASE_URL ?>home/otros-reportes" method="GET">
            <div class="row g-3">
                <div class="col-md-12 mb-3">
                    <label for="reporteTipo" class="form-label fw-bold">Seleccione el tipo de Reporte</label>
                    <select class="form-select" id="reporteTipo" name="reporte_tipo">
                        <option value="">Seleccione...</option>
                        <option value="fechas" <?= ($_GET['reporte_tipo'] ?? '') == 'fechas' ? 'selected' : '' ?>>Préstamos por Rango de Fechas</option>
                        <option value="cliente" <?= ($_GET['reporte_tipo'] ?? '') == 'cliente' ? 'selected' : '' ?>>Préstamos por Cliente</option>
                        <option value="linea_credito" <?= ($_GET['reporte_tipo'] ?? '') == 'linea_credito' ? 'selected' : '' ?>>Préstamos por Línea de Crédito</option>
                        <option value="reparticiones" <?= ($_GET['reporte_tipo'] ?? '') == 'reparticiones' ? 'selected' : '' ?>>Préstamos por Reparticiones</option>
                    </select>
                </div>
                <div id="filtroFechas" class="report-filter-section col-12" style="display: none;">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label for="filtroFecha" class="form-label fw-bold">Rango de Fechas</label>
                            <select class="form-select" id="filtroFecha" name="filtro_fecha">
                                <option value="all" <?= ($_GET['filtro_fecha'] ?? '') == 'all' ? 'selected' : '' ?>>Todos</option>
                                <option value="last_month" <?= ($_GET['filtro_fecha'] ?? '') == 'last_month' ? 'selected' : '' ?>>Último Mes</option>
                                <option value="last_year" <?= ($_GET['filtro_fecha'] ?? '') == 'last_year' ? 'selected' : '' ?>>Último Año</option>
                                <option value="custom" <?= ($_GET['filtro_fecha'] ?? '') == 'custom' ? 'selected' : '' ?>>Personalizado</option>
                            </select>
                        </div>
                        <div class="col-md-6" id="rangoFechasPersonalizado" style="display: none;">
                            <label class="form-label fw-bold">Fechas Personalizadas</label>
                            <div class="d-flex">
                                <input type="text" class="form-control me-2" id="fecha_inicio" name="fecha_inicio" placeholder="Fecha de Inicio" value="<?= $_GET['fecha_inicio'] ?? '' ?>">
                                <input type="text" class="form-control" id="fecha_fin" name="fecha_fin" placeholder="Fecha de Fin" value="<?= $_GET['fecha_fin'] ?? '' ?>">
                            </div>
                        </div>
                    </div>
                </div>
                <div id="filtroCliente" class="report-filter-section col-md-6" style="display: none;">
                    <label for="campoCliente" class="form-label fw-bold">Filtrar por Cliente</label>
                    <input type="text" class="form-control" id="campoCliente" name="filtro_cliente" placeholder="Nombre o ID del cliente" value="<?= $_GET['filtro_cliente'] ?? '' ?>">
                </div>
                <div id="filtroLineaCredito" class="report-filter-section col-md-6" style="display: none;">
                    <label for="campoLineaCreditoSelect" class="form-label fw-bold">Seleccione Línea de Crédito</label>
                    <select class="form-select" id="campoLineaCreditoSelect" name="filtro_linea_credito">
                        <option value="">Todas las Líneas</option>
                        <?php 
                        // Asegúrate de que $lineas_credito_disponibles sea un array de strings (o de objetos con la propiedad 'linea_credito')
                        $linea_seleccionada = $_GET['filtro_linea_credito'] ?? '';
                        foreach (($lineas_credito_disponibles ?? []) as $linea): 
                            // Asumo que $linea es el nombre de la línea de crédito
                            $nombre_linea = is_array($linea) ? $linea['linea_credito'] : $linea;
                        ?>
                            <option value="<?= htmlspecialchars($nombre_linea) ?>" 
                                    <?= ($linea_seleccionada == $nombre_linea) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nombre_linea) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="filtroReparticiones" class="report-filter-section col-md-6" style="display: none;">
                    <label for="campoReparticionSelect" class="form-label fw-bold">Seleccione Repartición</label>
                    <select class="form-select" id="campoReparticionSelect" name="filtro_reparticion">
                        <option value="">Todas las Reparticiones</option>
                        <?php 
                        // Asegúrate de que $reparticiones_disponibles sea un array de strings (o de objetos con la propiedad 'reparticion')
                        $reparticion_seleccionada = $_GET['filtro_reparticion'] ?? '';
                        foreach (($reparticiones_disponibles ?? []) as $reparticion): 
                            // Asumo que $reparticion es el nombre de la repartición
                            $nombre_reparticion = is_array($reparticion) ? $reparticion['reparticion'] : $reparticion;
                        ?>
                            <option value="<?= htmlspecialchars($nombre_reparticion) ?>" 
                                    <?= ($reparticion_seleccionada == $nombre_reparticion) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($nombre_reparticion) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            <div class="col-12 text-center mt-4">
                <button type="submit" class="btn btn-primary btn-lg">Generar Reporte</button>
                <?php if (isset($resultados_reporte_personalizado)): ?>
                    <button type="button" class="btn btn-secondary btn-lg" onclick="window.print()"><i class="fas fa-print me-2"></i>Imprimir</button>
                <?php endif; ?>
            </div>
        </form>
    </div>

    <div id="reporteCompleto" class="mt-4">
        
        <div id="graficoContainer" class="report-container" style="display: none;">
            <h4 id="tituloGrafico" class="mb-4 text-center"></h4>
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <canvas id="canvasGrafico"></canvas>
                </div>
            </div>
        </div>

        <?php if (isset($resultados_reporte_personalizado)): ?>
            <div class="report-container mt-4">
                <h4 class="mb-3"><?= htmlspecialchars($titulo_reporte_personalizado) ?></h4>
                <div class="table-responsive">
                    <?php if (!empty($resultados_reporte_personalizado)): ?>
                        <table class="table table-hover table-striped" id="reporteTabla"> <thead class="bg-custom">
                            <tr>
                                <th>Número de Solicitud</th>
                                <th>Cliente</th>
                                <th>Importe</th>
                                <th>Fecha de Solicitud</th>
                                <th>Estado</th>
                                <th>Línea de Crédito</th>
                                <th>Repartición</th> </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($resultados_reporte_personalizado as $prestamo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($prestamo['numero_solicitud']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['nombre_cliente'] . ' ' . $prestamo['apellido_cliente']) ?></td>
                                    <td>$<?= number_format($prestamo['importe'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($prestamo['fecha_solicitud']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['estado']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['linea_credito']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['reparticion'] ?? 'N/A') ?></td> </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    <?php else: ?>
                        <p class="text-center mt-3">No se encontraron resultados para su búsqueda.</p>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const reporteTipo = document.getElementById('reporteTipo');
        const reporteForm = document.getElementById('reporteForm');
        const seccionesFiltro = document.querySelectorAll('.report-filter-section');
        const filtroFecha = document.getElementById('filtroFecha');
        const rangoFechasPersonalizado = document.getElementById('rangoFechasPersonalizado');
        const graficoContainer = document.getElementById('graficoContainer');
        const tituloGrafico = document.getElementById('tituloGrafico');
        let myChart = null; 

        // Datos de PHP para gráficos
        const dataLineasCredito = <?= json_encode($top_lineas_credito ?? []) ?>;
        const labelsLineasCredito = dataLineasCredito.map(item => item.linea_credito);
        const valuesLineasCredito = dataLineasCredito.map(item => item.total_prestamos);
        
        const dataPrestamosPorMes = <?= json_encode($prestamos_por_mes ?? []) ?>;
        const labelsPrestamosPorMes = dataPrestamosPorMes.map(item => item.mes);
        const valuesPrestamosPorMes = dataPrestamosPorMes.map(item => item.total_prestamos);
        
        const dataTopReparticiones = <?= json_encode($top_reparticiones ?? []) ?>;
        const labelsTopReparticiones = dataTopReparticiones.map(item => item.reparticion);
        const valuesTopReparticiones = dataTopReparticiones.map(item => item.total_prestamos);

        function inicializarFlatpickr() {
            // ... (Tu función inicializarFlatpickr, sin cambios) ...
            const fechaInicio = document.getElementById('fecha_inicio');
            const fechaFin = document.getElementById('fecha_fin');
            if (fechaInicio && fechaFin) {
                if (fechaInicio._flatpickr) fechaInicio._flatpickr.destroy();
                if (fechaFin._flatpickr) fechaFin._flatpickr.destroy();

                flatpickr(fechaInicio, {
                    dateFormat: "Y-m-d",
                });
                flatpickr(fechaFin, {
                    dateFormat: "Y-m-d",
                });
            }
        }

        function mostrarFiltro(tipo) {
            seccionesFiltro.forEach(section => {
                section.style.display = 'none';
            });
            
            let seccionMostrarId = '';
            
            // Mapeo explícito para evitar problemas con snake_case (linea_credito)
            switch (tipo) {
                case 'fechas':
                    seccionMostrarId = 'filtroFechas';
                    break;
                case 'cliente':
                    seccionMostrarId = 'filtroCliente';
                    break;
                case 'linea_credito':
                    // Usa el ID exacto de tu HTML: filtroLineaCredito
                    seccionMostrarId = 'filtroLineaCredito'; 
                    break;
                case 'reparticiones':
                    // Usa el ID exacto de tu HTML: filtroReparticiones
                    seccionMostrarId = 'filtroReparticiones';
                    break;
                default:
                    return; 
            }
            
            const seccionMostrar = document.getElementById(seccionMostrarId);
            if (seccionMostrar) {
                seccionMostrar.style.display = 'block';
            }
        }
        // **********************************************
        
        function generarGrafico(tipo) {
            // ... (Tu función generarGrafico, sin cambios) ...
            if (myChart) {
                myChart.destroy();
            }

            let chartData = {};
            let chartType = '';
            let chartTitle = '';
            let tieneDatos = false;

            switch (tipo) {
                case 'fechas':
                    if (dataPrestamosPorMes && dataPrestamosPorMes.length > 0) {
                        chartTitle = 'Préstamos por Mes';
                        chartType = 'line';
                        chartData = {
                            labels: labelsPrestamosPorMes,
                            datasets: [{
                                label: 'Cantidad de Préstamos',
                                data: valuesPrestamosPorMes,
                                borderColor: 'rgb(75, 192, 192)',
                                tension: 0.1
                            }]
                        };
                        tieneDatos = true;
                    }
                    break;
                case 'linea_credito':
                    if (dataLineasCredito && dataLineasCredito.length > 0) {
                        chartTitle = 'Top 10 Líneas de Crédito más Usadas';
                        chartType = 'bar';
                        chartData = {
                            labels: labelsLineasCredito,
                            datasets: [{
                                label: 'Total de Préstamos',
                                data: valuesLineasCredito,
                                backgroundColor: 'rgba(54, 162, 235, 0.5)',
                                borderColor: 'rgba(54, 162, 235, 1)',
                                borderWidth: 1
                            }]
                        };
                        tieneDatos = true;
                    }
                    break;
                case 'reparticiones':
                    if (dataTopReparticiones && dataTopReparticiones.length > 0) {
                        chartTitle = 'Top 10 Reparticiones';
                        chartType = 'pie';
                        chartData = {
                            labels: labelsTopReparticiones,
                            datasets: [{
                                label: 'Total de Préstamos',
                                data: valuesTopReparticiones,
                                backgroundColor: ['rgba(255, 99, 132, 0.6)', 'rgba(54, 162, 235, 0.6)', 'rgba(255, 206, 86, 0.6)', 'rgba(75, 192, 192, 0.6)', 'rgba(153, 102, 255, 0.6)', 'rgba(255, 159, 64, 0.6)'],
                                borderColor: ['rgba(255, 99, 132, 1)', 'rgba(54, 162, 235, 1)', 'rgba(255, 206, 86, 1)', 'rgba(75, 192, 192, 1)', 'rgba(153, 102, 255, 1)', 'rgba(255, 159, 64, 1)'],
                                borderWidth: 1
                            }]
                        };
                        tieneDatos = true;
                    }
                    break;
                default:
                    // No hay gráfico para los otros tipos de reporte
                    break;
            }

            if (tieneDatos) {
                graficoContainer.style.display = 'block';
                tituloGrafico.textContent = chartTitle;
                const ctx = document.getElementById('canvasGrafico').getContext('2d');
                myChart = new Chart(ctx, {
                    type: chartType,
                    data: chartData,
                    options: {
                        responsive: true,
                        maintainAspectRatio: true
                    }
                });
            } else {
                graficoContainer.style.display = 'none';
            }
        }
        
        // **Lógica sin cambios:** Envía el formulario automáticamente al cambiar el select
        reporteTipo.addEventListener('change', function() {
            // Envía el formulario
            reporteForm.submit();
        });

        // Event listener para el filtro de fecha específico
        filtroFecha.addEventListener('change', function() {
            if (this.value === 'custom') {
                rangoFechasPersonalizado.style.display = 'block';
                inicializarFlatpickr();
            } else {
                rangoFechasPersonalizado.style.display = 'none';
            }
        });

        // Lógica para mantener los filtros y gráficos visibles al recargar la página
        const tipoSeleccionadoAlCargar = reporteTipo.value;
        if (tipoSeleccionadoAlCargar) {
            mostrarFiltro(tipoSeleccionadoAlCargar);
            generarGrafico(tipoSeleccionadoAlCargar); 
            
            if (tipoSeleccionadoAlCargar === 'fechas') {
                if (filtroFecha.value === 'custom') {
                    rangoFechasPersonalizado.style.display = 'block';
                    inicializarFlatpickr();
                }
            }
        }
        const reporteTabla = document.getElementById('reporteTabla');
        if (reporteTabla) {
            new DataTable(reporteTabla, {
                language: {
                    // Carga el paquete de idioma español para búsqueda, paginación, etc.
                    url: 'https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json' 
                },
                responsive: true, // Asegura que se vea bien en móviles
            });
        }
    });
</script>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>