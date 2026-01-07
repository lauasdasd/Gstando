<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Reporte Diario de Préstamos</h4>
        <a href="<?= BASE_URL ?>prestamos/imprimir-reporte-diario?fecha=<?= htmlspecialchars($fechaFiltro) ?>" target="_blank" class="btn btn-primary">
            <i class="fas fa-print"></i> Imprimir
        </a>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>prestamos/reporte-diario" method="GET" class="mb-4">
            <div class="row g-3 align-items-center">
                <div class="col-auto">
                    <label for="fecha_filtro" class="col-form-label">Filtrar por fecha:</label>
                </div>
                <div class="col-auto">
                    <input type="date" class="form-control" id="fecha_filtro" name="fecha" value="<?= htmlspecialchars($fechaFiltro) ?>">
                </div>
                <div class="col-auto">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter"></i> Filtrar
                    </button>
                </div>
            </div>
        </form>

        <p>A continuación se listan los préstamos registrados en la fecha **<?= date('d/m/Y', strtotime($fechaFiltro)) ?>** por **<?= htmlspecialchars($_SESSION['nombre_usuario']) ?>**.</p>

        <?php if (!empty($prestamos)): ?>
            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>N° Solicitud</th>
                        <th>Cliente</th>
                        <th>Importe</th>
                        <th>Fecha de Inicio</th>
                        <th>Fecha de Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($prestamos as $prestamo): ?>
                    <tr>
                        <td><?= htmlspecialchars($prestamo['numero_solicitud']) ?></td>
                        <td>$<?= number_format($prestamo['importe'], 2) ?></td>
                        <td><?= htmlspecialchars($prestamo['cliente_nombre']) ?></td>
                        <td><?= date('d/m/Y', strtotime($prestamo['fecha_inicio'])) ?></td>
                        <td><?= date('d/m/Y', strtotime($prestamo['fecha_finalizacion'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <div class="alert alert-info" role="alert">
                No se han registrado préstamos en esta fecha.
            </div>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>