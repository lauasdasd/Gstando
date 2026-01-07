<?php require_once __DIR__ . '/../templates/header.php'; ?>

<div class="card">
    <div class="card-header">
        <h4>Crear Nuevo Cliente</h4>
    </div>
    <div class="card-body">
        <form action="<?= BASE_URL ?>clientes/guardar" method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nombre" class="form-label">Nombres</label>
                    <input type="text" class="form-control" id="nombre" name="nombre" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="apellido" class="form-label">Apellidos</label>
                    <input type="text" class="form-control" id="apellido" name="apellido" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="dni" class="form-label">DNI</label>
                    <input type="text" class="form-control" id="dni" name="dni" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="cuil" class="form-label">CUIL</label>
                    <input type="text" class="form-control" id="cuil" name="cuil">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fecha_nacimiento" class="form-label">Fecha de Nacimiento</label>
                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="lugar_nacimiento" class="form-label">Lugar de Nacimiento</label>
                    <input type="text" class="form-control" id="lugar_nacimiento" name="lugar_nacimiento" value="Chaco">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="nacionalidad" class="form-label">Nacionalidad</label>
                    <input type="text" class="form-control" id="nacionalidad" name="nacionalidad" value="Argentina">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="profesion" class="form-label">Profesión/Actividad</label>
                    <select class="form-control" id="profesion" name="profesion">
                        <option value="">Selecciona una opción</option>
                        <option value="Web SAMEEP">Web SAMEEP</option>
                        <option value="Adm Publica provincial">Adm Publica Provincial</option>
                        <option value="Web Municipalidad de PR Saenz Peña">Web Municipalidad de PR Saenz Peña</option>
                        <option value="Jubilado/Pensionado">Jubilado/Pensionado</option>
                        <option value="Web Secheep">Web Secheep</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="estado_civil" class="form-label">Estado Civil</label>
                    <select class="form-control" id="estado_civil" name="estado_civil">
                        <option value="">Selecciona una opción</option>
                        <option value="Soltero">Soltero</option>
                        <option value="Casado">Casado</option>
                        <option value="Divorciado">Divorciado</option>
                        <option value="Viudo">Viudo</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sexo</label>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sexo" id="sexo-femenino" value="Femenino">
                        <label class="form-check-label" for="sexo-femenino">
                            Femenino
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sexo" id="sexo-masculino" value="Masculino">
                        <label class="form-check-label" for="sexo-masculino">
                            Masculino
                        </label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="sexo" id="sexo-no-especificado" value="No especificado" checked>
                        <label class="form-check-label" for="sexo-no-especificado">
                            No especificado
                        </label>
                    </div>
                </div>
            </div>
            <div class="mb-3">
                <label for="seguro" class="form-label">Seguro</label>
                <input type="text" class="form-control" id="seguro" name="seguro" value="Tiene">
            </div>

            <hr>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="telefono" class="form-label">Celular</label>
                    <input type="text" class="form-control" id="telefono" name="telefono">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="text" class="form-control" id="email" name="email">
                </div>
            </div>
            <div class="mb-3">
                <label for="domicilio" class="form-label">Domicilio</label>
                <input type="text" class="form-control" id="domicilio" name="domicilio">
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="localidad" class="form-label">Localidad</label>
                    <input type="text" class="form-control" id="localidad" name="localidad" value="Pcia Roque Saenz Peña">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="codigo_postal" class="form-label">Código Postal</label>
                    <input type="text" class="form-control" id="codigo_postal" name="codigo_postal" value="3700">
                </div>
            </div>

            <button type="submit" class="btn btn-primary mt-3">Guardar Cliente</button>
            <a href="<?= BASE_URL ?>clientes" class="btn btn-secondary mt-3">Cancelar</a>
        </form>
    </div>
</div>

<?php require_once __DIR__ . '/../templates/footer.php'; ?>