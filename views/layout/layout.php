<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="build/js/app.js"></script>
    <link rel="shortcut icon" href="<?= asset('images/cit.png') ?>" type="image/x-icon">
    <link rel="stylesheet" href="<?= asset('build/styles.css') ?>">
    <title>Sistema Celulares</title>
</head>
<body>
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        
        <div class="container-fluid">

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarToggler" aria-controls="navbarToggler" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <!-- CAMBIADO: Ahora apunta a /inicio -->
            <a class="navbar-brand" href="/proyecto02_macs/inicio">
                <img src="<?= asset('./images/cit.png') ?>" width="35px'" alt="cit" >
                Sistema Celulares
            </a>
            <div class="collapse navbar-collapse" id="navbarToggler">
                
                <ul class="navbar-nav me-auto mb-2 mb-lg-0" style="margin: 0;">
                    <li class="nav-item">
                        <!-- CAMBIADO: Ahora apunta a /inicio -->
                        <a class="nav-link" aria-current="page" href="/proyecto02_macs/inicio"><i class="bi bi-house-fill me-2"></i>Inicio</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" style="background: none;" href="/proyecto02_macs/clientes">
                            <i class="bi bi-people-fill me-2"></i>Clientes
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" style="background: none;" href="/proyecto02_macs/usuarios">
                            <i class="bi bi-people-fill me-2"></i>Usuarios/Empleados
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" style="border: none; background: none;" href="/proyecto02_macs/marcas">
                            <i class="bi bi-tags-fill me-2"></i>Marcas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" style="border: none; background: none;" href="/proyecto02_macs/celulares">
                            <i class="bi bi-phone-fill me-2"></i>Celulares
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" style="background: none;" href="/proyecto02_macs/inventario">
                            <i class="bi bi-box-seam-fill me-2"></i>Inventario
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" style="background: none; border: none;" href="/proyecto02_macs/ventas">
                            <i class="bi bi-cart-fill me-2"></i>Ventas
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link px-3" style="border: none; background: none;" href="/proyecto02_macs/reparaciones">
                            <i class="bi bi-tools me-2"></i>Reparaciones
                        </a>
                    </li>

                    <div class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">
                            <i class="bi bi-bar-chart-fill me-2"></i>Reportes
                        </a>
                        <ul class="dropdown-menu dropdown-menu-dark" id="dropwdownRevision" style="margin: 0;">
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/proyecto02_macs/estadisticas"><i class="ms-lg-0 ms-2 bi bi-graph-up me-2"></i>Estadísticas</a>
                            </li>
                            <li>
                                <a class="dropdown-item nav-link text-white" href="/proyecto02_macs/historial"><i class="ms-lg-0 ms-2 bi bi-clock-history me-2"></i>Historial Ventas</a>
                            </li>
                        </ul>
                    </div> 

                </ul> 
                <div class="col-lg-1.5 d-grid mb-lg-0 mb-2">
                    <a href="/proyecto02_macs/" class="btn btn-danger"><i class="bi bi-arrow-bar-left"></i>Cerrar Sesión</a>
                </div>

            
            </div>
        </div>
        
    </nav>
    <div class="progress fixed-bottom" style="height: 6px;">
        <div class="progress-bar progress-bar-animated bg-danger" id="bar" role="progressbar" aria-valuemin="0" aria-valuemax="100"></div>
    </div>
    <div class="container-fluid pt-5 mb-4" style="min-height: 85vh">
        
        <?php echo $contenido; ?>
    </div>
    <div class="container-fluid">
        <div class="row justify-content-center text-center">
            <div class="col-12">
                <p style="font-size:xx-small; font-weight: bold;">
                        Comando de Informática y Tecnología, <?= date('Y') ?> &copy;
                </p>
            </div>
        </div>
    </div>
</body>
</html>