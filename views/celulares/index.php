<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">CELULARES</h3>
                    </div>
                    <form id="formCelular" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="celular_id" name="celular_id">
                        <input type="hidden" id="celular_fecha_creacion" name="celular_fecha_creacion" value="">
                        <input type="hidden" id="celular_situacion" name="celular_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-6">
                                <label for="celular_marca_id" class="form-label">Marca</label>
                                <select class="form-select form-select-lg" id="celular_marca_id" name="celular_marca_id" required>
                                    <option value="">Seleccione una marca</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="celular_modelo" class="form-label">Modelo</label>
                                <input type="text" class="form-control form-control-lg" id="celular_modelo" name="celular_modelo" placeholder="Ingrese modelo del celular" required>
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
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarCelulares">
                                <i class="bi bi-search me-2"></i>Buscar Celulares
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5" id="seccionTabla" style="display: none;">
        <div class="col-12 d-flex justify-content-center">
            <div class="card shadow-lg border-primary rounded-4" style="width: 90%;">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Celulares registrados en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableCelulares" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Marca</th>
                                    <th>Modelo</th>
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
<script src="<?= asset('build/js/celulares/index.js') ?>"></script>