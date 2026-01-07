<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h4>Crear Nuevo Préstamo</h4>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>prestamos/guardar" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="cliente_id" class="form-label">Cliente</label>
                    <select class="form-control" id="cliente_id" name="cliente_id" required>
                        <option value="">Selecciona un cliente</option>
                        <?php foreach ($clientes as $cliente): ?>
                            <option value="<?= htmlspecialchars($cliente['idCliente']) ?>">
                                <?= htmlspecialchars($cliente['nombre']) . ' ' . htmlspecialchars($cliente['apellido']) ?> (DNI: <?= htmlspecialchars($cliente['dni']) ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="numero_solicitud" class="form-label">Nro. Solicitud</label>
                    <input type="text" class="form-control" id="numero_solicitud" name="numero_solicitud" required>
                </div>
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="importe" class="form-label">Importe ($)</label>
                    <input type="number" step="0.01" class="form-control" id="importe" name="importe" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="importe_letras" class="form-label">Importe en Letras</label>
                    <input type="text" class="form-control" id="importe_letras" name="importe_letras" readonly>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="numero_cuotas" class="form-label">Número de Cuotas</label>
                    <input type="number" class="form-control" id="numero_cuotas" name="numero_cuotas" required>
                </div>
                <div class="col-md-8 mb-3">
                    <label for="linea_credito" class="form-label">Línea de Crédito</label>
                    <input type="text" class="form-control" id="linea_credito" name="linea_credito">
                </div>
            </div>
            <div class="mb-3">
                <label for="destino_prestamo" class="form-label">Destino del Préstamo</label>
                <input type="text" class="form-control" id="destino_prestamo" name="destino_prestamo" value="Destino no especificado">
            </div>

            <hr>

            <div class="row">
                <div class="col-md-4 mb-3">
                    <label for="caja_ahorro" class="form-label">Caja de Ahorro</label>
                    <input type="text" class="form-control" id="caja_ahorro" name="caja_ahorro">
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_solicitud" class="form-label">Fecha de Solicitud</label>
                    <input type="date" class="form-control" id="fecha_solicitud" name="fecha_solicitud" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_inicio" class="form-label">Fecha de Inicio</label>
                    <input type="date" class="form-control" id="fecha_inicio" name="fecha_inicio" value="<?= date('Y-m-d') ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label for="fecha_finalizacion" class="form-label">Fecha de Finalización</label>
                    <input type="date" class="form-control" id="fecha_finalizacion" name="fecha_finalizacion" required readonly>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="reparticion" class="form-label">Repartición</label>
                    <input type="text" class="form-control" id="reparticion" name="reparticion">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="estado_banco" class="form-label">Estado del Banco</label>
                    <input type="text" class="form-control" id="estado_banco" name="estado_banco" value="Pendiente" readonly>
                </div>
            </div>
            <div class="mb-3">
                <label for="observaciones" class="form-label">Observaciones</label>
                <textarea class="form-control" id="observaciones" name="observaciones" rows="3"></textarea>
            </div>
            
            <button type="submit" class="btn btn-primary">Guardar Préstamo</button>
            <a href="<?= BASE_URL ?>prestamos" class="btn btn-secondary">Cancelar</a>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const urlParams = new URLSearchParams(window.location.search);
        if (urlParams.has('error')) {
            if (urlParams.get('error') === 'duplicate') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al Guardar',
                    text: 'El número de solicitud de préstamo ya existe. Por favor, ingrese uno diferente.'
                });
            } else if (urlParams.get('error') === '1') {
                Swal.fire({
                    icon: 'error',
                    title: 'Error al Guardar',
                    text: 'Ocurrió un error inesperado al guardar el préstamo.'
                });
            }
        }

        // Script para calcular la fecha de finalización
        const fechaInicioInput = document.getElementById('fecha_inicio');
        const numeroCuotasInput = document.getElementById('numero_cuotas');
        const fechaFinalizacionInput = document.getElementById('fecha_finalizacion');

        function calcularFechaFinalizacion() {
            const fechaInicio = new Date(fechaInicioInput.value);
            const numeroCuotas = parseInt(numeroCuotasInput.value);

            if (fechaInicioInput.value && !isNaN(numeroCuotas) && numeroCuotas > 0) {
                const fechaFinalizacion = new Date(fechaInicio);
                fechaFinalizacion.setMonth(fechaFinalizacion.getMonth() + numeroCuotas);

                const año = fechaFinalizacion.getFullYear();
                // El mes es base 0, por lo que sumamos 1.
                const mes = (fechaFinalizacion.getMonth() + 1).toString().padStart(2, '0');
                const dia = fechaFinalizacion.getDate().toString().padStart(2, '0');

                fechaFinalizacionInput.value = `${año}-${mes}-${dia}`;
            } else {
                fechaFinalizacionInput.value = '';
            }
        }

        fechaInicioInput.addEventListener('change', calcularFechaFinalizacion);
        numeroCuotasInput.addEventListener('input', calcularFechaFinalizacion);
    });
</script>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>