<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">MODULO DE VENTAS</h3>
                    </div>
                    <form id="formVenta" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="venta_id" name="venta_id">
                        <input type="hidden" id="venta_fecha_creacion" name="venta_fecha_creacion" value="">
                        <input type="hidden" id="venta_situacion" name="venta_situacion" value="1">
                        <input type="hidden" id="venta_total" name="venta_total" value="0">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="venta_cliente_id" class="form-label">Cliente</label>
                                <select class="form-select form-select-lg" id="venta_cliente_id" name="venta_cliente_id" required>
                                    <option value="">Seleccione un cliente</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="venta_usuario_id" class="form-label">Vendedor</label>
                                <select class="form-select form-select-lg" id="venta_usuario_id" name="venta_usuario_id" required>
                                    <option value="">Seleccione un Vendedor</option>
                                </select>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-8">
                                <label for="producto_select" class="form-label">Producto</label>
                                <select class="form-select form-select-lg" id="producto_select">
                                    <option value="">Seleccione un producto</option>
                                </select>
                            </div>
                            <div class="col-md-2">
                                <label for="cantidad_input" class="form-label">Cantidad</label>
                                <input type="number" class="form-control form-control-lg" id="cantidad_input" placeholder="Cant." min="1">
                            </div>
                            <div class="col-md-2">
                                <label class="form-label">&nbsp;</label>
                                <button type="button" class="btn btn-success btn-lg w-100" id="BtnAgregarProducto">
                                    <i class="bi bi-plus me-1"></i>Agregar
                                </button>
                            </div>
                        </div>

                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <h5>Productos Seleccionados</h5>
                                <div class="table-responsive">
                                    <table class="table table-striped" id="tablaProductos">
                                        <thead>
                                            <tr>
                                                <th>Producto</th>
                                                <th>Precio</th>
                                                <th>Cantidad</th>
                                                <th>Subtotal</th>
                                                <th>Acciones</th>
                                            </tr>
                                        </thead>
                                        <tbody id="cuerpoTablaProductos">
                                        </tbody>
                                        <tfoot>
                                            <tr>
                                                <th colspan="3">TOTAL:</th>
                                                <th id="totalVenta">Q. 0.00</th>
                                                <th></th>
                                            </tr>
                                        </tfoot>
                                    </table>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-center gap-3">
                            <button class="btn btn-success btn-lg px-4 shadow" type="submit" id="BtnGuardar">
                                <i class="bi bi-save me-2"></i>Guardar Venta
                            </button>
                            <button class="btn btn-warning btn-lg px-4 shadow d-none" type="button" id="BtnModificar">
                                <i class="bi bi-pencil-square me-2"></i>Modificar
                            </button>
                            <button class="btn btn-secondary btn-lg px-4 shadow" type="reset" id="BtnLimpiar">
                                <i class="bi bi-eraser me-2"></i>Limpiar
                            </button>
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarVentas">
                                <i class="bi bi-search me-2"></i>Buscar Ventas
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
                    <h3 class="text-center text-primary mb-4">Ventas registradas en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableVentas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Cliente</th>
                                    <th>Vendedor</th>
                                    <th>Productos</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th>Fecha de Venta</th>
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
<script src="<?= asset('build/js/ventas/index.js') ?>"></script>