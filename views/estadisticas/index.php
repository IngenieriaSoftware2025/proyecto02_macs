<style>
    .stats-header {
        background: linear-gradient(135deg, #2c5aa0, #1e3f73);
        color: white;
        padding: 2rem;
        border-radius: 15px;
        margin-bottom: 2rem;
        text-align: center;
        box-shadow: 0 8px 25px rgba(44, 90, 160, 0.15);
    }
    
    .stats-card {
        background: white;
        border-radius: 15px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
        border: 1px solid #e3f2fd;
        margin-bottom: 2rem;
        transition: all 0.3s ease;
        overflow: hidden;
        height: 100%;
        min-height: 450px;
    }
    
    .stats-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(44, 90, 160, 0.15);
    }
    
    .stats-card-header {
        background: linear-gradient(135deg, #f8fbff, #e3f2fd);
        padding: 1rem 1.5rem;
        border-bottom: 1px solid #e3f2fd;
    }
    
    .stats-card-title {
        color: #2c5aa0;
        font-weight: 700;
        font-size: 1.1rem;
        margin: 0;
        text-align: center;
    }
    
    .stats-card-body {
        padding: 2rem 1.5rem;
    }
    
    .chart-container {
        position: relative;
        width: 100%;
        height: 320px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .chart-container canvas {
        width: 100% !important;
        height: 100% !important;
        max-width: 100%;
        object-fit: contain;
    }
    
    .stats-section {
        margin-bottom: 3rem;
    }
    
    .section-title {
        color: #2c5aa0;
        font-weight: 800;
        text-align: center;
        margin-bottom: 2rem;
        font-size: 1.8rem;
        position: relative;
    }
    
    .section-title::after {
        content: '';
        position: absolute;
        bottom: -8px;
        left: 50%;
        transform: translateX(-50%);
        width: 80px;
        height: 3px;
        background: linear-gradient(135deg, #2c5aa0, #1e3f73);
        border-radius: 2px;
    }
</style>

<div class="stats-header">
    <h1 class="mb-3">📊 PANEL DE ESTADÍSTICAS</h1>
    <p class="mb-0">Análisis completo del rendimiento del negocio</p>
</div>

<div class="stats-section">
    <h3 class="section-title">💰 ANÁLISIS DE VENTAS</h3>
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Marcas más vendidas</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico1"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Top 10 Celulares más vendidos</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico8"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Top 10 Clientes más frecuentes</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico3"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Ventas por cliente</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico7"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="stats-section">
    <h3 class="section-title">🔧 ANÁLISIS DE REPARACIONES</h3>
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Estados de reparaciones</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico2"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Servicios de reparación más solicitados</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico6"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Reparaciones por marca</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico9"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Empleados más productivos</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico4"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="stats-section">
    <h3 class="section-title">📦 INVENTARIO Y COMPARATIVAS</h3>
    <div class="row">
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Stock de inventario</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico5"></canvas>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6 mb-3">
            <div class="stats-card">
                <div class="stats-card-header">
                    <h5 class="stats-card-title">Ingresos: Ventas vs Reparaciones</h5>
                </div>
                <div class="stats-card-body">
                    <div class="chart-container">
                        <canvas id="grafico10"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="<?= asset('build/js/estadisticas/index.js') ?>"></script>