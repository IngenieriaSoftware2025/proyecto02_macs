<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">REGISTRO DE USUARIOS</h3>
                    </div>
                    <form id="formUsuario" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="usuario_id" name="usuario_id">
                        <input type="hidden" id="usuario_fecha_creacion" name="usuario_fecha_creacion" value="">
                        <input type="hidden" id="usuario_situacion" name="usuario_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="usuario_nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_nombre" name="usuario_nombre" placeholder="Ingrese nombre completo del usuario" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_dpi" class="form-label">DPI</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_dpi" name="usuario_dpi" placeholder="Ingrese DPI (13 dígitos)" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-lg" id="usuario_telefono" name="usuario_telefono" placeholder="Ingrese teléfono (8 dígitos)" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-lg" id="usuario_correo" name="usuario_correo" placeholder="ejemplo@ejemplo.com" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_puesto" class="form-label">Puesto</label>
                                <select class="form-select form-select-lg" id="usuario_puesto" name="usuario_puesto" required>
                                    <option value="">Seleccione un puesto</option>
                                    <option value="VENDEDOR">Vendedor</option>
                                    <option value="GERENTE">Gerente</option>
                                    <option value="CAJERO">Cajero</option>
                                    <option value="TECNICO">Técnico</option>
                                    <option value="SUPERVISOR">Supervisor</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="usuario_password" class="form-label">Contraseña</label>
                                <input type="password" class="form-control form-control-lg" id="usuario_password" name="usuario_password" placeholder="Ingrese contraseña (mínimo 6 caracteres)" required>
                            </div>
                            <div class="col-md-6">
                                <label for="usuario_rol" class="form-label">Rol</label>
                                <select class="form-select form-select-lg" id="usuario_rol" name="usuario_rol" required>
                                    <option value="">Seleccione un rol</option>
                                    <option value="ADMIN">Administrador</option>
                                    <option value="EMPLEADO">Empleado</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Guardar
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarUsuarios">
                                <i class="bi bi-search me-2"></i>Buscar Usuarios
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5" id="seccionTabla" style="display: none;">
        <div class="col-12 d-flex justify-content-center">
            <div class="card shadow-lg border-primary rounded-4" style="width: 95%;">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Empleados registrados por puesto</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableUsuarios" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nombre</th>
                                    <th>DPI</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Puesto</th>
                                    <th>Rol</th>
                                    <th>Fecha de Creación</th>
                                    <th>Situación</th>
                                    <th>Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/usuarios/index.js') ?>"></script>