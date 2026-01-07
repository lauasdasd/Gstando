<?php require_once __DIR__ . '/../templates/header.php'; ?>

<?php if (isset($_GET['success']) && $_GET['success'] == 1): ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        Swal.fire({
            icon: 'success',
            title: '¡Éxito!',
            text: 'El usuario se ha creado correctamente.',
            showConfirmButton: false,
            timer: 2500
        });
    });
</script>
<?php endif; ?>

<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h4>Listado de Usuarios</h4>
        <a href="<?= BASE_URL ?>usuarios/crear" class="btn btn-success">
            <i class="fas fa-plus"></i> Nuevo Usuario
        </a>
    </div>
    <div class="card-body">
        <table id="tabla-usuarios" class="table table-striped table-bordered" style="width:100%">
            <thead>
                <tr>
                    <th>Usuario</th>
                    <th>Nombre Completo</th>
                    <th>Email</th>
                    <th>Rol</th>
                    <th>Acciones</th> </tr>
            </thead>
            <tbody>
                <?php foreach ($usuarios as $usuario): 
                    // Asumo que tu objeto/array de usuario tiene una clave 'id' o 'id_usuario'
                    $id_usuario = $usuario['id'] ?? $usuario['id_usuario'] ?? null; 
                    if (!$id_usuario) continue; // Si no hay ID, salta la fila
                ?>
                <tr>
                    <td><?= htmlspecialchars($usuario['nombre_usuario']) ?></td>
                    <td><?= htmlspecialchars($usuario['nombre_completo']) ?></td>
                    <td><?= htmlspecialchars($usuario['email']) ?></td>
                    <td><?= htmlspecialchars($usuario['rol']) ?></td>
                    
                    <td>
                        <a href="<?= BASE_URL ?>usuarios/ver?id=<?= $usuario['id'] ?>" class="btn btn-sm btn-primary" title="Ver/Editar Usuario">
                            <i class="fas fa-edit"></i>
                        </a>
                        <a href="<?= BASE_URL ?>usuarios/eliminar/<?= $id_usuario ?>" class="btn btn-sm btn-danger eliminar-btn" title="Eliminar Usuario" data-id="<?= $id_usuario ?>">
                            <i class="fas fa-trash-alt"></i>
                        </a>
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
        $('#tabla-usuarios').DataTable({
            "language": {
                // Si la ruta local da error, usa la CDN estable:
                "url": "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json" 
            },
            // Desactiva la ordenación en la columna de Acciones
            "columnDefs": [
                { "orderable": false, "targets": [4] } // El índice 4 corresponde a la columna "Acciones"
            ]
        });

        // Lógica simple para el botón de eliminar con SweetAlert
        $('.eliminar-btn').on('click', function(e) {
            e.preventDefault();
            const url = $(this).attr('href');
            Swal.fire({
                title: '¿Está seguro?',
                text: "Esta acción no se puede deshacer.",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Sí, eliminar',
                cancelButtonText: 'Cancelar'
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = url;
                }
            });
        });
    });
</script>