<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4><i class="bi bi-search"></i> Módulo de Consultas de Clientes</h4>
        </div>
        <div class="card-body">
            <form id="form-consulta-apis" class="mb-4">
                <div class="row align-items-end">
                    <div class="col-md-2 mb-3 mb-md-0">
                        <label for="cuil_prefix" class="form-label">Prefijo CUIL</label>
                        <input type="text" class="form-control" id="cuil_prefix" name="cuil_prefix" placeholder="XX" maxlength="2" pattern="\d{2}" title="Ingrese dos dígitos" required>
                    </div>
                    <div class="col-md-6 mb-3 mb-md-0">
                        <label for="dni" class="form-label">DNI</label>
                        <input type="text" class="form-control" id="dni" name="dni" placeholder="XXXXXXXX" maxlength="8" pattern="\d{7,8}" title="Ingrese 7 u 8 dígitos" required>
                    </div>
                    <div class="col-md-2 mb-3 mb-md-0">
                        <label for="cuil_suffix" class="form-label">Sufijo CUIL</label>
                        <input type="text" class="form-control" id="cuil_suffix" name="cuil_suffix" placeholder="X" maxlength="1" pattern="\d{1}" title="Ingrese un dígito" required>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-binoculars"></i> Consultar APIs
                        </button>
                    </div>
                </div>
            </form>

            <div id="loading-spinner" class="text-center d-none">
                <div class="spinner-border text-primary" role="status">
                    <span class="visually-hidden">Cargando...</span>
                </div>
                <p class="mt-2">Realizando consultas, por favor espere...</p>
            </div>

            <div id="resultados-apis" class="mt-4 d-none">
                <h5 class="mb-3">Resultados de las Consultas:</h5>

                <div class="card mb-3">
                    <div class="card-header bg-info text-white">
                        <h6 class="mb-0"><i class="bi bi-bank"></i> Central de Deudores BCRA</h6>
                                <button id="btn-imprimir-reporte" class="btn btn-secondary mt-2 w-100" type="button">
                                    <i class="bi bi-printer"></i> Imprimir Reporte
                                </button>
                    </div>
                    <div class="card-body" id="bcra-resultados">
                        <p class="text-muted">No se ha realizado la consulta BCRA o no hay datos.</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-warning text-dark">
                        <h6 class="mb-0"><i class="bi bi-file-earmark-text"></i> DocuEst</h6>
                    </div>
                    <div class="card-body" id="docuest-resultados">
                        <p class="text-muted">No se ha realizado la consulta DocuEst o no hay datos.</p>
                    </div>
                </div>

                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <h6 class="mb-0"><i class="bi bi-building"></i> Sistema de Mutuales Ecom</h6>
                    </div>
                    <div class="card-body" id="ecom-resultados">
                        <p class="text-muted">No se ha realizado la consulta Ecom o no hay datos.</p>
                    </div>
                </div>

                <div class="text-end">
                    <button id="btn-guardar-consulta" class="btn btn-success" data-cliente-id="" data-cuil="">
                        <i class="bi bi-save"></i> Guardar y Vincular a Cliente
                    </button>
                </div>
            </div>
             <div id="mensaje-error" class="alert alert-danger mt-4 d-none" role="alert">
                Hubo un error al realizar las consultas. Por favor, inténtelo de nuevo.
            </div>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>
<script>
$(document).ready(function() {
    $('#form-consulta-apis').on('submit', function(e) {
        e.preventDefault(); 
        
        const cuil_prefix = $('#cuil_prefix').val();
        const dni = $('#dni').val();
        const cuil_suffix = $('#cuil_suffix').val();

        let identificacion;

        if (cuil_prefix && cuil_suffix) {
            identificacion = `${cuil_prefix}${dni}${cuil_suffix}`;
        } else if (dni) {
            identificacion = dni;
        } else {
            alert('Por favor, ingrese un CUIL o DNI para la consulta.');
            return;
        }

        $('#loading-spinner').removeClass('d-none');
        $('#resultados-apis').addClass('d-none');
        $('#mensaje-error').addClass('d-none');
        
        $.ajax({
            url: '<?= BASE_URL ?>consultas/procesar', 
            type: 'POST',
            dataType: 'json',
            data: { identificacion: identificacion }, 
            success: function(response) {
    $('#loading-spinner').addClass('d-none');
    
    // Si la API del BCRA devuelve un error (404, 400, etc.)
    if (response.bcra.status !== 200) {
        $('#mensaje-error').removeClass('d-none');
        const errorMsg = response.bcra.errorMessages ? response.bcra.errorMessages[0] : 'Error desconocido de la API BCRA.';
        $('#mensaje-error').text(errorMsg);
        
    } else { // Si la respuesta de la API es exitosa (status: 200)
        $('#resultados-apis').removeClass('d-none');
        
        // Extrae los datos de la respuesta JSON
        const bcraData = response.bcra.results;
        
        let bcraHtml = `
            <p><strong>Cliente ID:</strong> ${response.cliente_id || 'N/A'}</p>
            <p><strong>Nombre/Razón Social:</strong> ${bcraData.denominacion}</p>
            <p><strong>CUIL/DNI:</strong> ${bcraData.identificacion}</p>
        `;

        // Itera sobre todas las entidades en el primer período
        if (bcraData.periodos && bcraData.periodos.length > 0) {
            bcraData.periodos[0].entidades.forEach(entidad => {
                // Lógica para mostrar la situación de forma clara
                let situacionTexto = "Sin información";
                let situacionClase = "bg-secondary";
                switch(entidad.situacion) {
                    case 1: situacionTexto = "Situación Normal"; situacionClase = "bg-success"; break;
                    case 2: situacionTexto = "Situación 2 (Riesgo bajo)"; situacionClase = "bg-warning"; break;
                    case 3: situacionTexto = "Situación 3 (Riesgo medio)"; situacionClase = "bg-warning"; break;
                    case 4: situacionTexto = "Situación 4 (Riesgo alto)"; situacionClase = "bg-danger"; break;
                    case 5: situacionTexto = "Situación 5 (Riesgo muy alto)"; situacionClase = "bg-danger"; break;
                    case 6: situacionTexto = "Situación 6 (Riesgo inminente)"; situacionClase = "bg-danger"; break;
                    case 7: situacionTexto = "Situación 7 (Irrecuperable)"; situacionClase = "bg-danger"; break;
                    case 8: situacionTexto = "Irrecuperable por disposición técnica"; situacionClase = "bg-danger"; break;
                }

                bcraHtml += `
                    <div class="card mb-2">
                        <div class="card-body">
                            <p class="mb-1"><strong>Entidad:</strong> ${entidad.entidad}</p>
                            <p class="mb-1"><strong>Situación:</strong> <span class="badge ${situacionClase}">${situacionTexto}</span></p>
                            <p class="mb-1"><strong>Monto:</strong> $${parseFloat(entidad.monto || 0).toFixed(2)}</p>
                            <p class="mb-1"><strong>Días de Atraso:</strong> ${entidad.diasAtrasoPago}</p>
                        </div>
                    </div>
                `;
            });
        }
        
        $('#bcra-resultados').html(bcraHtml);

        $('#btn-guardar-consulta').attr('data-cliente-id', response.cliente_id || '');
        $('#btn-guardar-consulta').attr('data-cuil', identificacion);
    }
},
            error: function(xhr, status, error) {
                $('#loading-spinner').addClass('d-none');
                $('#mensaje-error').removeClass('d-none').text('Error en el servidor o la llamada no es válida.');
                console.error("Error AJAX: ", status, error, xhr.responseText);
            }
        });
    });

    $('#btn-guardar-consulta').on('click', function() {
        alert('Esta función se implementará en el siguiente paso.');
    });
    // Al final de tu script, justo antes del '});' final
// En tu script, en la lógica del clic del botón de imprimir...
$('#btn-imprimir-reporte').on('click', function() {
    const cuil = `${$('#cuil_prefix').val()}${$('#dni').val()}${$('#cuil_suffix').val()}`;
    // Cambiamos la URL para pasar el CUIL como un parámetro de consulta
    window.open(`<?= BASE_URL ?>consultas/generarReporte?cuil=${cuil}`, '_blank');
});
});
</script>

