<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Listado de Clientes</h4>
        <a href="<?= BASE_URL ?>clientes/crear" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Cliente
        </a>
    </div>
    <div class="card-body">
        <table id="tabla-clientes" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Nombre</th>
                    <th>DNI</th>
                    <th>Teléfono</th>
                    <th>Email</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($clientes as $cliente): ?>
                <tr>
                    <td><?= htmlspecialchars($cliente['nombre']) . ' ' . htmlspecialchars($cliente['apellido']) ?></td>
                    <td><?= htmlspecialchars($cliente['dni']) ?></td>
                    <td><?= htmlspecialchars($cliente['telefono']) ?></td>
                    <td><?= htmlspecialchars($cliente['email']) ?></td>
                    <td>
                        <a href="<?= BASE_URL ?>clientes/editar/<?= $cliente['idCliente'] ?>" class="btn btn-warning btn-sm">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="<?php echo BASE_URL . 'clientes/perfil?idCliente=' . htmlspecialchars($cliente['idCliente']); ?>"class="btn btn-success btn-sm">
                            <i class="bi bi-eye"></i>
                        </a>
                        <button class="btn btn-danger btn-sm" onclick="confirmarEliminar(<?= $cliente['idCliente'] ?>)">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>

<script>
    // Inicializar DataTables
    $(document).ready(function() {
        $('#tabla-clientes').DataTable({
            "language": {
                "url": "<?= BASE_URL ?>public/assets/js/es-ES.json" // Agrega un archivo para traducir
            }
        });
    });

    // Función para SweetAlert
    function confirmarEliminar(idCliente) {
        Swal.fire({
            title: '¿Estás seguro?',
            text: "No podrás revertir esto!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Sí, eliminar!',
            cancelButtonText: 'Cancelar'
        }).then((result) => {
            if (result.isConfirmed) {
                // Si el usuario confirma, redirigimos a una ruta para eliminar
                window.location.href = `<?= BASE_URL ?>clientes/eliminar/${idCliente}`;
            }
        });
    }
</script>