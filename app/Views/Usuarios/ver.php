<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="container my-5">
    <div class="card shadow">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Detalles y Edición de Usuario: <?= htmlspecialchars($usuario['nombre_usuario']) ?></h4>
        </div>
        <div class="card-body">
            <form action="<?= BASE_URL ?>usuarios/actualizar/<?= htmlspecialchars($usuario['id']) ?>" method="POST">
                
                <div class="mb-3">
                    <label for="nombre_usuario" class="form-label">Usuario:</label>
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" 
                           value="<?= htmlspecialchars($usuario['nombre_usuario']) ?>" required>
                </div>

                <div class="mb-3">
                    <label for="nombre_completo" class="form-label">Nombre Completo:</label>
                    <input type="text" class="form-control" id="nombre_completo" name="nombre_completo" 
                           value="<?= htmlspecialchars($usuario['nombre_completo']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="email" class="form-label">Email:</label>
                    <input type="email" class="form-control" id="email" name="email" 
                           value="<?= htmlspecialchars($usuario['email']) ?>" required>
                </div>
                
                <div class="mb-3">
                    <label for="rol" class="form-label">Rol:</label>
                    <select class="form-select" id="rol" name="rol" required>
                        <option value="user" <?= ($usuario['rol'] == 'user') ? 'selected' : '' ?>>Usuario Estándar</option>
                        <option value="admin" <?= ($usuario['rol'] == 'admin') ? 'selected' : '' ?>>Administrador</option>
                    </select>
                </div>
                
                <hr>
                
                <h5 class="mt-4">Cambiar Contraseña (Opcional)</h5>
                <div class="mb-3">
                    <label for="password" class="form-label">Nueva Contraseña:</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Dejar en blanco para no cambiar">
                </div>
                
                <div class="d-flex justify-content-between">
                    <a href="<?= BASE_URL ?>usuarios" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i> Volver al Listado
                    </a>
                    <button type="submit" class="btn btn-success">
                        <i class="fas fa-save me-1"></i> Guardar Cambios
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>