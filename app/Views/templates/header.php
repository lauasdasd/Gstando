<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gstando - Gestión de Préstamos</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/2.0.3/css/dataTables.bootstrap5.min.css">
<style>
    .navbar {
        /* Aquí usamos la URL de la imagen que me proporcionaste */
        background-image: url('<?= BASE_URL ?>public/assets/img/Fondo.jpg');
        background-size: cover; /* Asegura que la imagen cubra todo el área del navbar */
        background-repeat: no-repeat; /* Evita que la imagen se repita */
        background-position: center center; /* Centra la imagen en el navbar */
        background-color: #002244; /* Un color de respaldo por si la imagen no carga */
    }

    /* Ajustes para el texto y elementos de la barra de navegación para que se vean bien sobre el fondo */
    .navbar-dark .navbar-nav .nav-link {
        color: #FFFFFF; /* Texto blanco para mayor contraste */
        font-weight: bold; /* Opcional: hace el texto más legible */
    }

    .navbar-dark .navbar-brand {
        color: #FFFFFF;
        font-weight: bold;
    }

    .navbar-dark .navbar-toggler-icon {
        filter: invert(1); /* Para que el icono del toggler sea visible sobre el fondo oscuro */
    }

    .navbar-nav .nav-item .nav-link.active {
        /* Estilo para el enlace activo si deseas resaltarlo */
        background-color: rgba(255, 255, 255, 0.2); /* Un ligero fondo para el activo */
        border-radius: 5px;
    }

    /* Estilo para el dropdown de usuario */
    .navbar-nav .dropdown-menu {
        background-color: #002244; /* Fondo oscuro para el dropdown */
        border: none;
    }

    .navbar-nav .dropdown-item {
        color: #FFFFFF; /* Texto blanco en los ítems del dropdown */
    }

    .navbar-nav .dropdown-item:hover {
        background-color: rgba(255, 255, 255, 0.1); /* Hover ligero para ítems del dropdown */
    }

    .navbar-nav .dropdown-divider {
        border-top-color: rgba(255, 255, 255, 0.3); /* Color del divisor en el dropdown */
    }
</style>
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark">
        <div class="container-fluid">
        <a class="navbar-brand" href="<?= BASE_URL ?>">
            <img src="<?= BASE_URL ?>public/assets/img/Gestando.png" alt="Gstando Logo" height="50" >
        </a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" aria-current="page" href="<?= BASE_URL ?>clientes">Clientes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>prestamos">Préstamos</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>prestamos/reporte-diario">Reporte Diario</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="<?= BASE_URL ?>consultas">
                        <i class="bi bi-search"></i> Consultas
                    </a>
                </li>
                <?php if (isset($_SESSION['rol']) && $_SESSION['rol'] === 'admin'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="<?= BASE_URL ?>usuarios">Usuarios</a>
                    </li>
                <?php endif; ?>
            </ul>

            <?php if (isset($_SESSION['nombre_usuario'])): ?>
                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="fas fa-user-circle"></i>
                            Hola, <?= htmlspecialchars($_SESSION['nombre_usuario']) ?>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                            
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>perfil">
                                    <i class="fas fa-address-card me-2"></i> Ver Perfil
                                </a>
                            </li>
                            
                            <li><hr class="dropdown-divider"></li>
                            
                            <li>
                                <a class="dropdown-item" href="<?= BASE_URL ?>logout">
                                    <i class="fas fa-sign-out-alt me-2"></i> Cerrar Sesión
                                </a>
                            </li>
                        </ul>
                    </li>
                </ul>
            <?php endif; ?>
        </div>
    </div>
</nav>

<div class="container mt-4">

<div class="container mt-4">
<?php require_once __DIR__ . '/../templates/footer.php'; ?>