<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">REGISTRO DE CLIENTES</h3>
                    </div>
                    <form id="formCliente" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="cliente_id" name="cliente_id">
                        <input type="hidden" id="cliente_fecha_creacion" name="cliente_fecha_creacion" value="">
                        <input type="hidden" id="cliente_situacion" name="cliente_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="cliente_nombre" class="form-label">Nombre Completo</label>
                                <input type="text" class="form-control form-control-lg" id="cliente_nombre" name="cliente_nombre" placeholder="Ingrese nombre completo del cliente" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="cliente_dpi" class="form-label">DPI</label>
                                <input type="text" class="form-control form-control-lg" id="cliente_dpi" name="cliente_dpi" placeholder="Ingrese DPI (13 dígitos)" required>
                            </div>
                            <div class="col-md-6">
                                <label for="cliente_telefono" class="form-label">Teléfono</label>
                                <input type="text" class="form-control form-control-lg" id="cliente_telefono" name="cliente_telefono" placeholder="Ingrese teléfono (8 dígitos)" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="cliente_correo" class="form-label">Correo Electrónico</label>
                                <input type="email" class="form-control form-control-lg" id="cliente_correo" name="cliente_correo" placeholder="ejemplo@ejemplo.com" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-4">
                            <div class="col-md-12">
                                <label for="cliente_direccion" class="form-label">Dirección</label>
                                <input type="text" class="form-control form-control-lg" id="cliente_direccion" name="cliente_direccion" placeholder="Ingrese dirección completa" required>
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
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarClientes">
                                <i class="bi bi-search me-2"></i>Buscar Clientes
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
                    <h3 class="text-center text-primary mb-4">Clientes registrados en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableClientes" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nombre</th>
                                    <th>DPI</th>
                                    <th>Teléfono</th>
                                    <th>Correo</th>
                                    <th>Dirección</th>
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
<script src="<?= asset('build/js/clientes/index.js') ?>"></script>