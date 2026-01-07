<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container-fluid mt-4">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Detalles del Préstamo</h4>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <h5>Información del Cliente</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nombre Completo:</strong> <?= htmlspecialchars($prestamo['nombre']) . ' ' . htmlspecialchars($prestamo['apellido']) ?></li>
                        <li class="list-group-item"><strong>DNI:</strong> <?= htmlspecialchars($prestamo['dni']) ?></li>
                    </ul>
                    <button type="button" class="btn btn-secondary btn-sm mt-3" data-bs-toggle="modal" data-bs-target="#clienteModal" data-cliente-id="<?= htmlspecialchars($prestamo['cliente_id']) ?>">
                        Ver Datos del Cliente
                    </button>
                </div>
                <div class="col-md-6">
                    <h5>Detalles de la Solicitud</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Nro. de Solicitud:</strong> <?= htmlspecialchars($prestamo['numero_solicitud']) ?></li>
                        <li class="list-group-item"><strong>Línea de Crédito:</strong> <?= htmlspecialchars($prestamo['linea_credito']) ?></li>
                        <li class="list-group-item"><strong>Repartición:</strong> <?= htmlspecialchars($prestamo['reparticion']) ?></li>
                        <li class="list-group-item"><strong>Destino del Préstamo:</strong> <?= htmlspecialchars($prestamo['destino_prestamo']) ?></li>
                    </ul>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row">
                <div class="col-md-6">
                    <h5>Valores y Cuotas</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Importe:</strong> $<?= number_format($prestamo['importe'], 2, ',', '.') ?></li>
                        <li class="list-group-item"><strong>Importe en Letras:</strong> <?= htmlspecialchars($prestamo['importe_letras']) ?></li>
                        <li class="list-group-item"><strong>Número de Cuotas:</strong> <?= htmlspecialchars($prestamo['numero_cuotas']) ?></li>
                    </ul>
                </div>
                <div class="col-md-6">
                    <h5>Fechas y Estado</h5>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><strong>Fecha de Solicitud:</strong> <?= date('d/m/Y', strtotime(htmlspecialchars($prestamo['fecha_solicitud']))) ?></li>
                        <li class="list-group-item"><strong>Fecha de Inicio:</strong> <?= date('d/m/Y', strtotime(htmlspecialchars($prestamo['fecha_inicio']))) ?></li>
                        <li class="list-group-item"><strong>Fecha de Finalización:</strong> <?= date('d/m/Y', strtotime(htmlspecialchars($prestamo['fecha_finalizacion']))) ?></li>
                        <li class="list-group-item"><strong>Estado:</strong> <span class="badge bg-success"><?= htmlspecialchars($prestamo['estado']) ?></span></li>
                        <li class="list-group-item"><strong>Estado de Banco:</strong> <span class="badge bg-info text-dark"><?= htmlspecialchars($prestamo['estado_banco']) ?></span></li>
                    </ul>
                </div>
            </div>
            
            <div class="mt-4">
                <h5>Observaciones</h5>
                <p class="card-text border rounded p-3 bg-light"><?= nl2br(htmlspecialchars($prestamo['observaciones'])) ?></p>
            </div>
            <div class="mt-4 text-center">
                <a href="<?= BASE_URL ?>prestamos" class="btn btn-secondary">Volver al Listado</a>
                <a href="/Gstando/prestamos/caratula?id=<?= htmlspecialchars($prestamo['id']) ?>" class="btn btn-primary" target="_blank">Imprimir Carátula</a>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="clienteModal" tabindex="-1" aria-labelledby="clienteModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="clienteModalLabel">Detalles del Cliente</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div id="clienteModalBody">Cargando...</div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const clienteModal = document.getElementById('clienteModal');
        clienteModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const clienteId = button.getAttribute('data-cliente-id');
            const modalBody = clienteModal.querySelector('#clienteModalBody');

            // Cargar los datos del cliente
            fetch(`<?= BASE_URL ?>clientes/modal_cliente?idCliente=${clienteId}`)
                .then(response => response.text())
                .then(html => {
                    modalBody.innerHTML = html;
                })
                .catch(error => {
                    modalBody.innerHTML = '<p class="text-danger">Error al cargar los datos del cliente.</p>';
                    console.error('Error:', error);
                });
        });
    });
</script>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>