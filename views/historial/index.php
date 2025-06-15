<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">HISTORIAL DE VENTAS</h3>
                    </div>
                    <form id="formFiltros" class="p-4 bg-white rounded-3 shadow-sm border">
                        <div class="row g-4 mb-3">
                            <div class="col-md-4">
                                <label for="fecha_inicio" class="form-label">Fecha Inicio</label>
                                <input type="date" class="form-control form-control-lg" id="fecha_inicio" name="fecha_inicio">
                            </div>
                            <div class="col-md-4">
                                <label for="fecha_fin" class="form-label">Fecha Fin</label>
                                <input type="date" class="form-control form-control-lg" id="fecha_fin" name="fecha_fin">
                            </div>
                            <div class="col-md-4">
                                <label for="tipo_filtro" class="form-label">Tipo de Transacción</label>
                                <select class="form-select form-select-lg" id="tipo_filtro" name="tipo_filtro">
                                    <option value="todos">Todas las transacciones</option>
                                    <option value="venta">Solo ventas</option>
                                    <option value="reparacion">Solo reparaciones</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarHistorial">
                                <i class="bi bi-search me-2"></i>Buscar Historial
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiarFiltros">
                                <i class="bi bi-eraser me-2"></i>Limpiar Filtros
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
                    <h3 class="text-center text-primary mb-4">Historial de transacciones organizadas por tipo</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableHistorial" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Tipo</th>
                                    <th>Cliente</th>
                                    <th>Empleado</th>
                                    <th>Descripción</th>
                                    <th>Monto</th>
                                    <th>Estado</th>
                                    <th>Fecha</th>
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

<div class="modal fade" id="modalDetalle" tabindex="-1" aria-labelledby="modalDetalleLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalDetalleLabel">Detalles de Transacción</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="contenidoDetalle">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="<?= asset('build/js/historial/index.js') ?>"></script>