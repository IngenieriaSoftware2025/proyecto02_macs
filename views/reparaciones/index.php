<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">MODULO DE REPARACIONES</h3>
                    </div>
                    <form id="formReparacion" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="reparacion_id" name="reparacion_id">
                        <input type="hidden" id="reparacion_fecha_creacion" name="reparacion_fecha_creacion" value="">
                        <input type="hidden" id="reparacion_situacion" name="reparacion_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="reparacion_cliente_id" class="form-label">Cliente</label>
                                <select class="form-select form-select-lg" id="reparacion_cliente_id" name="reparacion_cliente_id" required>
                                    <option value="">Seleccione un cliente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="reparacion_usuario_id" class="form-label">Empleado que recibe</label>
                                <select class="form-select form-select-lg" id="reparacion_usuario_id" name="reparacion_usuario_id" required>
                                    <option value="">Seleccione un empleado</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="reparacion_marca" class="form-label">Marca del Dispositivo</label>
                                <input type="text" class="form-control form-control-lg" id="reparacion_marca" name="reparacion_marca" placeholder="Ej: Samsung, iPhone, Xiaomi" required>
                            </div>
                            <div class="col-md-6">
                                <label for="reparacion_tipo_celular" class="form-label">Modelo del Dispositivo</label>
                                <input type="text" class="form-control form-control-lg" id="reparacion_tipo_celular" name="reparacion_tipo_celular" placeholder="Ej: Galaxy S21, iPhone 13 Pro" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="reparacion_motivo" class="form-label">Motivo de la Reparación</label>
                                <textarea class="form-control form-control-lg" id="reparacion_motivo" name="reparacion_motivo" placeholder="Describa el problema del dispositivo" rows="3" required></textarea>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="reparacion_trabajador" class="form-label">Trabajador/Técnico</label>
                                <select class="form-select form-select-lg" id="reparacion_trabajador" name="reparacion_trabajador" required>
                                    <option value="">Seleccione un técnico</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="reparacion_servicio" class="form-label">Tipo de Servicio</label>
                                <input type="text" class="form-control form-control-lg" id="reparacion_servicio" name="reparacion_servicio" placeholder="Ej: Cambio de pantalla, formateo" required>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-4">
                                <label for="reparacion_estado" class="form-label">Estado</label>
                                <select class="form-select form-select-lg" id="reparacion_estado" name="reparacion_estado" required>
                                    <option value="">Seleccione un estado</option>
                                    <option value="recibido">Recibido</option>
                                    <option value="en_proceso">En Proceso</option>
                                    <option value="finalizado">Finalizado</option>
                                </select>
                            </div>
                            <div class="col-md-4">
                                <label for="reparacion_precio" class="form-label">Precio</label>
                                <input type="number" class="form-control form-control-lg" id="reparacion_precio" name="reparacion_precio" placeholder="Ingrese precio" step="0.01" min="0.01" required>
                            </div>
                            <div class="col-md-4">
                                <label for="reparacion_fecha_entrega" class="form-label">Fecha de Entrega</label>
                                <input type="date" class="form-control form-control-lg" id="reparacion_fecha_entrega" name="reparacion_fecha_entrega">
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Guardar Reparación
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarReparaciones">
                                <i class="bi bi-search me-2"></i>Buscar Reparaciones
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5" id="seccionTabla" style="display: none;">
        <div class="col-12 d-flex justify-content-center">
            <div class="card shadow-lg border-primary rounded-4" style="width: 98%;">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Reparaciones registradas en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableReparaciones" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Cliente</th>
                                    <th>Empleado</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
                                    <th>Motivo</th>
                                    <th>Técnico</th>
                                    <th>Servicio</th>
                                    <th>Estado</th>
                                    <th>Precio</th>
                                    <th>Fecha Entrega</th>
                                    <th>Fecha Creación</th>
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
<script src="<?= asset('build/js/reparaciones/index.js') ?>"></script>