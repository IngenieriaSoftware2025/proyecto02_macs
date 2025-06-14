<style>
    body {
        background: linear-gradient(135deg, rgb(109, 109, 109) 0%, rgb(179, 188, 204) 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        min-height: 100vh;
        background-color: #f8f9fa;
    }

    .header {
        padding: 2rem;
        text-align: center;
        border-radius: 15px;
        margin-top: 2rem;
        margin-bottom: 2rem;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .logo {
        font-size: 3rem;
        font-weight: bold;
        color: #2d3748;
        margin-bottom: 1rem;
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .container {
        max-width: 1140px;
        margin-left: auto;
        margin-right: auto;
    }
    
    .product-img {
        border-radius: 10px;
        width: 100%;
        height: 200px;
        max-height: 300px;
        object-fit: cover;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        margin-bottom: 1rem;
        background-color: #e9ecef;
        display: flex;
        align-items: center;
        justify-content: center;
        color: #6c757d;
        font-size: 1.2rem;
        text-align: center;
        overflow: hidden;
    }

    .product-img:hover {
        transform: scale(1.05) rotate(-1deg);
        box-shadow: 0 8px 24px rgba(0, 0, 0, 0.3);
        cursor: pointer;
    }

    .product-img img {
        width: 100%;
        height: 100%;
        border-radius: 10px;
        object-fit: cover;
    }
    
    .card {
        transition: transform 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
    }
</style>

<body>
    <div class="header">
        <div class="logo">¡Bienvenido al Sistema de Celulares!</div>
    </div>
    
    <div class="container">
        <div class="row mb-5">
            <div class="col-md-8 mx-auto text-center">
                <p class="lead">
                    "Administra de manera eficiente tu negocio de celulares con módulos para inventario, ventas, reparaciones y clientes. Controla el stock, gestiona usuarios y mantén un registro completo de forma sencilla y profesional."
                </p>
            </div>
        </div>
        
        <div class="row mb-4">
            <div class="col-12 text-center mb-4">
                <h2 class="text-uppercase fw-bold">Módulos del Sistema</h2>
                <p class="text-muted">Gestiona todos los aspectos de tu negocio desde una sola plataforma.</p>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://www.questionpro.com/blog/wp-content/uploads/2016/08/Portada-gestion-de-clientes.jpg" alt="Gestión de Clientes" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-people-fill me-2 text-primary"></i>Gestión de Clientes
                        </h5>
                        <p class="card-text text-muted">Administra tu base de clientes con información completa y segura.</p>
                        <a href="/proyecto02_macs/clientes" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://cdn-icons-png.flaticon.com/512/10691/10691856.png" alt="Usuarios/Empleados" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-people-fill me-2 text-success"></i>Usuarios/Empleados
                        </h5>
                        <p class="card-text text-muted">Gestiona empleados y usuarios del sistema con roles específicos.</p>
                        <a href="/proyecto02_macs/usuarios" class="btn btn-success">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://down-br.img.susercontent.com/file/br-11134207-7r98o-lxy2q2lgji4yfa" alt="Marcas" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-tags-fill me-2 text-warning"></i>Marcas
                        </h5>
                        <p class="card-text text-muted">Administra las marcas de celulares disponibles en tu negocio.</p>
                        <a href="/proyecto02_macs/marcas" class="btn btn-warning">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://noticias.bidcom.com.ar/wp-content/uploads/2023/10/Samsung-vs.-Motorola-1.jpg" alt="Celulares" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-phone-fill me-2 text-info"></i>Celulares
                        </h5>
                        <p class="card-text text-muted">Gestiona el catálogo de modelos y especificaciones de celulares.</p>
                        <a href="/proyecto02_macs/celulares" class="btn btn-info">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://img.freepik.com/vector-gratis/personal-tienda-verifica-cantidad-productos-que-deben-entregar-clientes-dia_1150-51079.jpg" alt="Inventario" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-box-seam-fill me-2 text-secondary"></i>Inventario
                        </h5>
                        <p class="card-text text-muted">Controla el stock, precios y disponibilidad de productos.</p>
                        <a href="/proyecto02_macs/inventario" class="btn btn-secondary">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://soporte.mygestion.com/media/wp-content/uploads/ventas.jpg" alt="Ventas" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-cart-fill me-2 text-success"></i>Ventas
                        </h5>
                        <p class="card-text text-muted">Procesa ventas, gestiona facturación y controla transacciones.</p>
                        <a href="/proyecto02_macs/ventas" class="btn btn-success">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://www.guatemala.com/fotos/201709/portada-885x500.jpg" alt="Reparaciones" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-tools me-2 text-danger"></i>Reparaciones
                        </h5>
                        <p class="card-text text-muted">Administra el servicio técnico y reparaciones de dispositivos.</p>
                        <a href="/proyecto02_macs/reparaciones" class="btn btn-danger">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://s1.significados.com/foto/estadistica-como-herramienta-de-analisis.jpg?class=article" alt="Estadísticas" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-graph-up me-2 text-primary"></i>Estadísticas
                        </h5>
                        <p class="card-text text-muted">Analiza el rendimiento del negocio con reportes y gráficos.</p>
                        <a href="/proyecto02_macs/estadisticas" class="btn btn-primary">Acceder</a>
                    </div>
                </div>
            </div>
            
            <div class="col-md-4 mb-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-img-top product-img">
                        <img src="https://active-campaign.transforms.svdcdn.com/production/general/wp/2022_04_sales-methodology.png?w=1600&q=80&auto=format&fit=clip&dm=1732095556&s=996c257e59bdf6b3808da3bff06971cd" alt="Historial de Ventas" style="display: block;">
                    </div>
                    <div class="card-body text-center">
                        <h5 class="card-title fw-bold">
                            <i class="bi bi-clock-history me-2 text-info"></i>Historial de Ventas
                        </h5>
                        <p class="card-text text-muted">Consulta el historial completo de transacciones y actividades.</p>
                        <a href="/proyecto02_macs/historial" class="btn btn-info">Acceder</a>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="row mt-5">
            <div class="col-md-6 mx-auto text-center">
                <h2 class="mb-4 text-uppercase fw-bold">Características Principales</h2><br>
                <p><strong>Organiza:</strong> mantén ordenados todos los datos de clientes, inventario y ventas.</p>
                <p><strong>Controla:</strong> gestiona el stock automáticamente y supervisa las reparaciones.</p>
                <p><strong>Administra:</strong> supervisa todo el negocio desde una interfaz intuitiva.</p>
                <a href="/proyecto02_macs/clientes" class="btn btn-primary btn-lg mt-3">Comenzar a administrar</a>
            </div>
        </div>
    </div>
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <script src="<?= asset('build/js/inicio.js') ?>"></script>
</body>