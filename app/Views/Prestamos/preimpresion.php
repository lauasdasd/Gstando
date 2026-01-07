<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-warning text-dark">
            <h4 class="mb-0">Revisar y Corregir para Imprimir</h4>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>prestamos/imprimirCaratula" method="POST" target="_blank">
                <input type="hidden" name="id" value="<?= htmlspecialchars($prestamo['id']) ?>">
                
                <div class="row">
                    <div class="col-md-6 mb-4">
                        <h5>Datos del Cliente</h5>
                        <div class="mb-3">
                            <label for="nombre_completo" class="form-label">Nombre Completo</label>
                            <input type="text" class="form-control" name="nombre_completo" value="<?= htmlspecialchars($prestamo['nombre']) . ' ' . htmlspecialchars($prestamo['apellido']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="dni" class="form-label">DNI</label>
                            <input type="text" class="form-control" name="dni" value="<?= htmlspecialchars($prestamo['dni']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="cuil" class="form-label">CUIL</label>
                            <input type="text" class="form-control" name="cuil" value="<?= htmlspecialchars($prestamo['cuil']) ?>" readonly>
                        </div>
                        <div class="mb-3">
                            <label for="telefono" class="form-label">Teléfono</label>
                            <input type="text" class="form-control" name="telefono" value="<?= htmlspecialchars($prestamo['telefono']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="domicilio" class="form-label">Domicilio</label>
                            <input type="text" class="form-control" name="domicilio" value="<?= htmlspecialchars($prestamo['domicilio']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="localidad" class="form-label">Localidad</label>
                            <input type="text" class="form-control" name="localidad" value="<?= htmlspecialchars($prestamo['localidad']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="codigo_postal" class="form-label">Código Postal</label>
                            <input type="text" class="form-control" name="codigo_postal" value="<?= htmlspecialchars($prestamo['codigo_postal']) ?>">
                        </div>
                    </div>

                    <div class="col-md-6 mb-4">
                        <h5>Datos del Préstamo</h5>
                        <div class="mb-3">
                            <label for="importe" class="form-label">Importe</label>
                            <input type="text" class="form-control" name="importe" value="<?= htmlspecialchars($prestamo['importe']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="caja_ahorro" class="form-label">Caja de Ahorro</label>
                            <input type="text" class="form-control" name="caja_ahorro" value="<?= htmlspecialchars($prestamo['caja_ahorro']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="seguro" class="form-label">Seguro</label>
                            <input type="text" class="form-control" name="seguro" value="<?= htmlspecialchars($prestamo['seguro']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="reparticion" class="form-label">Repartición</label>
                            <input type="text" class="form-control" name="reparticion" value="<?= htmlspecialchars($prestamo['reparticion']) ?>">
                        </div>
                        <div class="mb-3">
                            <label for="observaciones" class="form-label">Observaciones</label>
                            <textarea class="form-control" name="observaciones" rows="3"><?= htmlspecialchars($prestamo['observaciones']) ?></textarea>
                        </div>
                    </div>
                </div>

                <div class="text-center mt-3">
                    <button type="submit" class="btn btn-success">Generar e Imprimir Carátula</button>
                    <a href="<?= BASE_URL ?>prestamos/ver?id=<?= htmlspecialchars($prestamo['id']) ?>" class="btn btn-secondary">Cancelar</a>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>