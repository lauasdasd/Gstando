<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reporte Diario - <?= date('d/m/Y', strtotime($fechaFiltro)) ?></title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/bootstrap.min.css">
    <style>
        @media print {
            body { font-size: 10pt; }
            .table-sm th, .table-sm td { padding: 0.3rem; }
            .no-print { display: none; }
            hr.page-break {
                border-top: 1px dashed #000;
                margin: 2rem 0;
            }
        }
    </style>
</head>
<body onload="window.print();">

<div class="container mt-4">
    <div class="text-center mb-4">
        <h3>Carpetas entregadas el día: <?= date('d/m/Y', strtotime($fechaFiltro)) ?></h3>
        <p>Registradas por: **<?= htmlspecialchars($_SESSION['nombre_usuario']) ?>**</p>
    </div>

    <?php if (!empty($prestamos)): ?>
        <table class="table table-bordered table-striped table-sm">
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
                    <td><?= htmlspecialchars($prestamo['cliente_nombre']) ?></td>
                    <td>$<?= number_format($prestamo['importe'], 2) ?></td>
                    <td><?= date('d/m/Y', strtotime($prestamo['fecha_inicio'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($prestamo['fecha_finalizacion'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se han registrado préstamos en esta fecha.</p>
    <?php endif; ?>
</div>

<hr class="page-break">
<p class="text-center no-print">---------- **Corte aquí** ----------</p>

<div class="container mt-4">
    <div class="text-center mb-4">
        <h3>Carpetas entregadas el día: <?= date('d/m/Y', strtotime($fechaFiltro)) ?></h3>
        <p>Registradas por: **<?= htmlspecialchars($_SESSION['nombre_usuario']) ?>**</p>
    </div>

    <?php if (!empty($prestamos)): ?>
        <table class="table table-bordered table-striped table-sm">
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
                    <td><?= htmlspecialchars($prestamo['cliente_nombre']) ?></td>
                    <td>$<?= number_format($prestamo['importe'], 2) ?></td>
                    <td><?= date('d/m/Y', strtotime($prestamo['fecha_inicio'])) ?></td>
                    <td><?= date('d/m/Y', strtotime($prestamo['fecha_finalizacion'])) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>No se han registrado préstamos en esta fecha.</p>
    <?php endif; ?>
</div>

</body>
</html>