<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gstando - Iniciar Sesión</title>
    <link rel="stylesheet" href="<?= BASE_URL ?>public/assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<style>
body {
    background: linear-gradient(135deg, #7ebeffff 0%, #00912e 100%);
    height: 100vh;
    display: flex;
    justify-content: center;
    align-items: center;
    font-family: Arial, sans-serif;
}
.login-card {
    background-color: #ffffff;
    border-radius: 10px;
    /* Usamos una sombra más pronunciada para que resalte */
    box-shadow: 0 20px 35px rgba(0, 0, 0, 0.8);
    width: 100%;
    max-width: 420px;
    overflow: hidden;
    text-align: center;
}
.login-header {
    background-image: url('<?= BASE_URL ?>public/assets/img/Fondo.jpg');
    background-size: cover;
    background-position: center;
    padding: 10px;
    position: relative;
    color:  #ffffff;
}
.logo {
    width: auto;
    height: 60px;
    margin-bottom: 20px;
    /* Esto ayuda a que el logo se vea mejor sobre el fondo */
    filter: drop-shadow(0 0 5px rgba(0, 0, 0, 0.5));
}
.form-title {
    font-size: 1.5rem;
    font-weight: bold;
    color: #ffffffff; /* El color principal del texto, que es blanco */
    margin-bottom: 20px;
    padding: 0 30px; /* Separamos el título del borde */
    /* Agrega esta línea para el borde de las letras */
    text-shadow: 
        -1px -1px 0 #000,
        1px -1px 0 #000,
        -1px 1px 0 #000,
        1px 1px 0 #000;
}
.login-card-body {
    padding: 30px;
}
.form-control-icon {
    position: relative;
    margin-bottom: 15px;
}
.form-control-icon .form-control {
    padding-left: 40px;
}
.form-control-icon .fa-solid {
    position: absolute;
    top: 50%;
    left: 15px;
    transform: translateY(-50%);
    color: #24507cff;
}
.btn-primary {
    background-color: #002244;
    border-color: #002244;
    width: 100%;
    margin-top: 10px;
}
.btn-primary:hover {
    background-color: #003366;
    border-color: #003366;
}
</style>
</head>
<body>
    <div class="login-card">
        <div class="login-header">
            <img src="<?= BASE_URL ?>public/assets/img/Gestando.png" alt="Logo de Gstando" class="logo">
            <hr>
            <h3 class="form-title">Iniciar Sesión</h3>
        </div>
        <div class="login-card-body">
            
            <?php if (isset($error)): ?>
                <div class="alert alert-danger text-center" role="alert">
                    <?= htmlspecialchars($error) ?>
                </div>
            <?php endif; ?>

            <form action="<?= BASE_URL ?>login" method="POST" autocomplete="off">
                <div class="form-control-icon">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" class="form-control" id="nombre_usuario" name="nombre_usuario" placeholder="Usuario" required autocomplete="off">
                </div>
                <div class="form-control-icon">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" class="form-control" id="contrasena" name="contrasena" placeholder="Contraseña" required autocomplete="off">
                </div>
                <button type="submit" class="btn btn-primary">Ingresar</button>
            </form>
        </div>
    </div>
    <script src="<?= BASE_URL ?>public/assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>