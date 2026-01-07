<?php require_once __DIR__ . '/../templates/header.php'; ?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gstando - Dashboard</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    
    <style>
        body {
            background-color: #f0f4f8;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .summary-card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s ease-in-out;
        }
        .summary-card:hover {
            transform: translateY(-5px);
        }
        .summary-card .card-body {
            padding: 20px;
        }
        .summary-card .card-icon {
            font-size: 2rem;
            color: #fff;
            padding: 15px;
            border-radius: 8px;
            opacity: 0.8;
        }
        .card-active .card-icon { background-color: #28a745; }
        .card-other .card-icon { background-color: #696969ff; }
        .card-finished .card-icon { background-color: #007bff; }
        .card-overdue .card-icon { background-color: #ffc107; }
        
        .report-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
            margin-bottom: 30px;
        }
        h1, h3 {
            font-weight: 600;
        }
        html {
            scroll-behavior: smooth;
        }
        .flatpickr-input {
            width: 100%;
        }
        .report-filter-section {
            display: none;
        }
    </style>
</head>
<body>

<div class="container">
    <h1 class="mb-5 text-center text-primary">Panel de Control de Préstamos</h1>

    <div class="row mb-5">
        <div class="col-md-3">
            <a href="#reporte-activos" class="text-decoration-none" data-bs-toggle="collapse">
                <div class="card summary-card text-center card-active">
                    <div class="card-body d-flex align-items-center justify-content-center flex-column">
                        <i class="fas fa-chart-line card-icon mb-3"></i>
                        <h5 class="card-title">Activos</h5>
                        <h2 class="card-text"><?= htmlspecialchars($resumen_por_estado['Activo'] ?? 0) ?></h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="#reporte-por-finalizar" class="text-decoration-none" data-bs-toggle="collapse">
                <div class="card summary-card text-center card-overdue">
                    <div class="card-body d-flex align-items-center justify-content-center flex-column">
                        <i class="fas fa-exclamation-triangle card-icon mb-3"></i>
                        <h5 class="card-title">Por finalizar</h5>
                        <h2 class="card-text"><?= htmlspecialchars(count($prestamos_por_finalizar)) ?></h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="#reporte-finalizados" class="text-decoration-none" data-bs-toggle="collapse">
                <div class="card summary-card text-center card-finished">
                    <div class="card-body d-flex align-items-center justify-content-center flex-column">
                        <i class="fas fa-check-circle card-icon mb-3"></i>
                        <h5 class="card-title">Finalizados</h5>
                        <h2 class="card-text"><?= htmlspecialchars($resumen_por_estado['Finalizado'] ?? 0) ?></h2>
                    </div>
                </div>
            </a>
        </div>

        <div class="col-md-3">
            <a href="<?= BASE_URL ?>home/otros-reportes" class="text-decoration-none">
                <div class="card summary-card text-center card-other">
                    <div class="card-body d-flex align-items-center justify-content-center flex-column">
                        <i class="fas fa-bars card-icon mb-3"></i>
                        <h5 class="card-title">Otros Reportes</h5>
                        <h2 class="card-text">Ver Más</h2>
                    </div>
                </div>
            </a>
        </div>
    </div>
    
    <div id="accordion-reports">
        <div class="report-container collapse show" id="reporte-activos" data-bs-parent="#accordion-reports">
            <h3 class="mb-3 d-flex align-items-center"><i class="fas fa-chart-line me-2 text-success"></i> Préstamos Activos</h3>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Número de Solicitud</th>
                            <th>Cliente</th>
                            <th>Importe</th>
                            <th>Fecha de Finalización</th>
                            <th>Estado</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($prestamos_activos)): ?>
                            <?php foreach ($prestamos_activos as $prestamo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($prestamo['numero_solicitud']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['nombre_cliente'] . ' ' . $prestamo['apellido_cliente']) ?></td>
                                    <td>$<?= number_format($prestamo['importe'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($prestamo['fecha_finalizacion']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['estado']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="5" class="text-center">No hay préstamos activos en este momento.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="report-container collapse" id="reporte-por-finalizar" data-bs-parent="#accordion-reports">
            <h3 class="mb-3 d-flex align-items-center"><i class="fas fa-calendar-alt me-2 text-warning"></i> Préstamos por finalizar en los próximos 30 días</h3>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Número de Solicitud</th>
                            <th>Cliente</th>
                            <th>Importe</th>
                            <th>Fecha de Finalización</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($prestamos_por_finalizar)): ?>
                            <?php foreach ($prestamos_por_finalizar as $prestamo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($prestamo['numero_solicitud']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['nombre_cliente'] . ' ' . $prestamo['apellido_cliente']) ?></td>
                                    <td>$<?= number_format($prestamo['importe'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($prestamo['fecha_finalizacion']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay préstamos por finalizar en los próximos 30 días.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <div class="report-container collapse" id="reporte-finalizados" data-bs-parent="#accordion-reports">
            <h3 class="mb-3 d-flex align-items-center"><i class="fas fa-history me-2 text-info"></i> Préstamos finalizados en los últimos 3 meses</h3>
            <div class="table-responsive">
                <table class="table table-hover table-striped">
                    <thead class="table-dark">
                        <tr>
                            <th>Número de Solicitud</th>
                            <th>Cliente</th>
                            <th>Importe</th>
                            <th>Fecha de Finalización</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($prestamos_finalizados)): ?>
                            <?php foreach ($prestamos_finalizados as $prestamo): ?>
                                <tr>
                                    <td><?= htmlspecialchars($prestamo['numero_solicitud']) ?></td>
                                    <td><?= htmlspecialchars($prestamo['nombre_cliente'] . ' ' . $prestamo['apellido_cliente']) ?></td>
                                    <td>$<?= number_format($prestamo['importe'], 2, ',', '.') ?></td>
                                    <td><?= htmlspecialchars($prestamo['fecha_finalizacion']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="4" class="text-center">No hay préstamos finalizados en los últimos 3 meses.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

</body>
</html>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>