<div class="container-fluid">
    <div class="row">
        <div class="col-md-6">
            <h5>Información Personal</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Nombre Completo:</strong> <?= htmlspecialchars($cliente['nombre']) . ' ' . htmlspecialchars($cliente['apellido']) ?></li>
                <li class="list-group-item"><strong>DNI:</strong> <?= htmlspecialchars($cliente['dni']) ?></li>
                <li class="list-group-item"><strong>CUIL:</strong> <?= htmlspecialchars($cliente['cuil']) ?></li>
                <li class="list-group-item"><strong>Fecha de Nacimiento:</strong> <?= date('d/m/Y', strtotime(htmlspecialchars($cliente['fecha_nacimiento']))) ?></li>
                <li class="list-group-item"><strong>Lugar de Nacimiento:</strong> <?= htmlspecialchars($cliente['lugar_nacimiento']) ?></li>
                <li class="list-group-item"><strong>Nacionalidad:</strong> <?= htmlspecialchars($cliente['nacionalidad']) ?></li>
                <li class="list-group-item"><strong>Estado Civil:</strong> <?= htmlspecialchars($cliente['estado_civil']) ?></li>
                <li class="list-group-item"><strong>Sexo:</strong> <?= htmlspecialchars($cliente['sexo']) ?></li>
                <li class="list-group-item"><strong>Profesión:</strong> <?= htmlspecialchars($cliente['profesion']) ?></li>
                <li class="list-group-item"><strong>Seguro:</strong> <?= htmlspecialchars($cliente['seguro']) ?></li>
            </ul>
        </div>
        <div class="col-md-6">
            <h5>Información de Contacto</h5>
            <ul class="list-group list-group-flush">
                <li class="list-group-item"><strong>Celular:</strong> <?= htmlspecialchars($cliente['telefono']) ?></li>
                <li class="list-group-item"><strong>Email:</strong> <?= htmlspecialchars($cliente['email']) ?></li>
                <li class="list-group-item"><strong>Domicilio:</strong> <?= htmlspecialchars($cliente['domicilio']) ?></li>
                <li class="list-group-item"><strong>Localidad:</strong> <?= htmlspecialchars($cliente['localidad']) ?></li>
                <li class="list-group-item"><strong>Código Postal:</strong> <?= htmlspecialchars($cliente['codigo_postal']) ?></li>
            </ul>
        </div>
    </div>
</div>