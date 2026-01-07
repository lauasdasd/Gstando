<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container">
    <div class="row">
        <div class="col-12">
            <h2>Perfil del Cliente</h2>
            <p><strong><?= htmlspecialchars($cliente['nombre'] . ' ' . $cliente['apellido']) ?></strong></p>
            <hr>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Datos Personales</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>DNI:</strong> <?= htmlspecialchars($cliente['dni']) ?></p>
                    <p><strong>CUIL:</strong> <?= htmlspecialchars($cliente['cuil']) ?></p>
                    <p><strong>Fecha de Nacimiento:</strong> <?= date('d/m/Y', strtotime($cliente['fecha_nacimiento'])) ?></p>
                    <p><strong>Lugar de Nacimiento:</strong> <?= htmlspecialchars($cliente['lugar_nacimiento']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Nacionalidad:</strong> <?= htmlspecialchars($cliente['nacionalidad']) ?></p>
                    <p><strong>Profesión:</strong> <?= htmlspecialchars($cliente['profesion']) ?></p>
                    <p><strong>Estado Civil:</strong> <?= htmlspecialchars($cliente['estado_civil']) ?></p>
                    <p><strong>Sexo:</strong> <?= htmlspecialchars($cliente['sexo']) ?></p>
                    <p><strong>Seguro:</strong> <?= htmlspecialchars($cliente['seguro']) ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0">Contacto y Domicilio</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Celular:</strong> <?= htmlspecialchars($cliente['telefono']) ?></p>
                    <p><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></p>
                </div>
                <div class="col-md-6">
                    <p><strong>Domicilio:</strong> <?= htmlspecialchars($cliente['domicilio']) ?></p>
                    <p><strong>Localidad:</strong> <?= htmlspecialchars($cliente['localidad']) ?></p>
                    <p><strong>Código Postal:</strong> <?= htmlspecialchars($cliente['codigo_postal']) ?></p>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card mb-4">
        <div class="card-header bg-info text-white">
            <h5 class="mb-0">Préstamos del Cliente</h5>
        </div>
        <div class="card-body">
            <?php if (!empty($prestamos)): ?>
                <table class="table table-bordered table-striped table-sm">
                    <thead>
                        <tr>
                            <th>N° Solicitud</th>
                            <th>Importe</th>
                            <th>Fecha de Inicio</th>
                            <th>Fecha de Finalización</th>
                            <th>Estado</th>
                            <th>Estado del Banco</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($prestamos as $prestamo): ?>
                            <tr>
                                <td><?= htmlspecialchars($prestamo['numero_solicitud']) ?></td>
                                <td>$<?= number_format($prestamo['importe'], 2) ?></td>
                                <td><?= date('d/m/Y', strtotime($prestamo['fecha_inicio'])) ?></td>
                                <td><?= date('d/m/Y', strtotime($prestamo['fecha_finalizacion'])) ?></td>
                                <td><span class="badge bg-secondary"><?= htmlspecialchars($prestamo['estado']) ?></span></td>
                                <td><span class="badge bg-info"><?= htmlspecialchars($prestamo['estado_banco']) ?></span></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>Este cliente no tiene préstamos registrados.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>