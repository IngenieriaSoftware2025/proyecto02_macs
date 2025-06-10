<div class="container py-5">
    <div class="row mb-5 justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-lg border-0 rounded-4">
                <div class="card-body bg-gradient" style="background: linear-gradient(90deg, #f8fafc 60%, #e3f2fd 100%);">
                    <div class="mb-4 text-center">
                        <h5 class="fw-bold text-secondary mb-2">¡Bienvenido a Nuestra Aplicación!</h5>
                        <h3 class="fw-bold text-primary mb-0">MARCAS DE CELULARES</h3>
                    </div>
                    <form id="formMarca" class="p-4 bg-white rounded-3 shadow-sm border">
                        <input type="hidden" id="marca_id" name="marca_id">
                        <input type="hidden" id="marca_fecha_creacion" name="marca_fecha_creacion" value="">
                        <input type="hidden" id="marca_situacion" name="marca_situacion" value="1">
                        
                        <div class="row g-4 mb-3">
                            <div class="col-md-12">
                                <label for="marca_nombre" class="form-label">Nombre de la Marca</label>
                                <input type="text" class="form-control form-control-lg" id="marca_nombre" name="marca_nombre" placeholder="Ingrese nombre de la marca" required>
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
                            <button class="btn btn-primary btn-lg px-4 shadow" type="button" id="BtnBuscarMarcas">
                                <i class="bi bi-search me-2"></i>Buscar Marcas
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row justify-content-center mt-5" id="seccionTabla" style="display: none;">
        <div class="col-12 d-flex justify-content-center">
            <div class="card shadow-lg border-primary rounded-4" style="width: 85%;">
                <div class="card-body">
                    <h3 class="text-center text-primary mb-4">Marcas registradas en la base de datos</h3>

                    <div class="table-responsive">
                        <table class="table table-striped table-hover table-bordered align-middle rounded-3 overflow-hidden w-100" id="TableMarcas" style="width: 100% !important;">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Nombre de la Marca</th>
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
<script src="<?= asset('build/js/marcas/index.js') ?>"></script>