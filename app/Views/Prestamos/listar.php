<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Listado de Préstamos</h4>
        <a href="<?= BASE_URL ?>prestamos/crear" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Préstamo
        </a>
    </div>
    <div class="card-body">
        <table id="tabla-prestamos" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>N° Solicitud</th>
                    <th>Cliente</th>
                    <th>Línea de Crédito</th>
                    <th>Importe</th>
                    <th>Fecha de Inicio</th>
                    <th>
                        Atendió<br>
                        <select id="filtro-atendio" class="form-select form-select-sm">
                            <option value="">Todos</option>
                            <?php foreach ($usuarios as $usuario): ?>
                                <option value="<?= htmlspecialchars($usuario['nombre_completo']) ?>">
                                    <?= htmlspecialchars($usuario['nombre_completo']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </th>
                    <th>Estado</th>
                    <th>Estado del Banco</th>   
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="cambiarEstadoModal" tabindex="-1" aria-labelledby="cambiarEstadoModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cambiarEstadoModalLabel">Cambiar Estado del Préstamo</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form-cambiar-estado" action="<?= BASE_URL ?>prestamos/actualizarEstado" method="POST">
                    <input type="hidden" name="prestamo_id" id="prestamo_id">
                    <div class="mb-3">
                        <label for="estado_banco" class="form-label">Seleccione el nuevo estado:</label>
                        <select class="form-select" name="estado_banco" id="estado_banco" required>
                            <option value="Aprobado">Aprobado</option>
                            <option value="Devuelto">Devuelto</option>
                            <option value="Rechazado">Rechazado</option>
                            <option value="Reingreso">Reingreso</option>
                            <option value="Enviado">Enviado</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
<script>
$(document).ready(function () {
    // 1. Script para pasar el ID del préstamo al modal (tu código original)
    var cambiarEstadoModal = document.getElementById('cambiarEstadoModal');
    cambiarEstadoModal.addEventListener('show.bs.modal', function(event) {
        var button = event.relatedTarget;
        var prestamoId = button.getAttribute('data-prestamo-id');
        var modalInput = cambiarEstadoModal.querySelector('#prestamo_id');
        modalInput.value = prestamoId;
    });

    // 2. Extensión de DataTables para búsqueda sin tildes ni mayúsculas
    $.extend($.fn.dataTable.ext.type.search, {
        string: function (data) {
            return !data ? '' :
                typeof data === 'string' ?
                    data
                        .normalize("NFD")
                        .replace(/[\u0300-\u036f]/g, "")
                        .toLowerCase() :
                    data;
        }
    });


    // 4. Inicializar la tabla con DataTables
    const table = $('#tabla-prestamos').DataTable({
        ajax: {
            url: '<?= BASE_URL ?>prestamos/data',
            type: 'GET',
            dataType: 'json',
            dataSrc: 'data',
            data: function (d) {
                d.fechaDesde = $('#fecha-desde').val();
                d.fechaHasta = $('#fecha-hasta').val();
            }
        },
        columns: [
            { data: 'numero_solicitud' },
            { data: 'cliente_nombre' },
            { data: 'linea_credito' },
            { data: 'importe' },
            { data: 'fecha_inicio' },
            { data: 'atendio' },
            { data: 'estado' },
            { data: 'estado_banco' },
            { data: null, orderable: false, render: function(data, type, row) {
                // Botones de acciones personalizados
                return `
                    <button class="btn btn-sm btn-success" data-bs-toggle="modal" data-bs-target="#cambiarEstadoModal" data-prestamo-id="${row.id}">
                        <i class="bi bi-pencil"></i>
                    </button>
                    <a href="<?= BASE_URL ?>prestamos/ver?id=${row.id}" class="btn btn-info btn-sm">Ver</a>
                `;
            }}
        ],
        language: {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });

    // 5. Agregar el evento de cambio para el select del filtro
    $('#filtro-atendio').on('change', function () {
        var val = $.fn.dataTable.util.escapeRegex($(this).val());
        // El índice 5 corresponde a la columna "Atendió"
        table.column(5).search(val ? '^' + val + '$' : '', true, false).draw();
    });

});
</script>