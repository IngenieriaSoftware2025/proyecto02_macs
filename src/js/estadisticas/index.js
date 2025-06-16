import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from "../funciones";
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";
import { Chart } from "chart.js/auto";

const grafico1 = document.getElementById("grafico1").getContext("2d");
const grafico2 = document.getElementById("grafico2").getContext("2d");
const grafico3 = document.getElementById("grafico3").getContext("2d");
const grafico4 = document.getElementById("grafico4").getContext("2d");
const grafico5 = document.getElementById("grafico5").getContext("2d");
const grafico6 = document.getElementById("grafico6").getContext("2d");
const grafico7 = document.getElementById("grafico7").getContext("2d");
const grafico8 = document.getElementById("grafico8").getContext("2d");
const grafico9 = document.getElementById("grafico9").getContext("2d");
const grafico10 = document.getElementById("grafico10").getContext("2d");

window.graficaMarcasVendidas = new Chart(grafico1, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Marcas Más Vendidas' },
            legend: { display: false }
        },
        scales: { y: { beginAtZero: true } }
    }
});

window.graficaEstadosReparaciones = new Chart(grafico2, {
    type: 'pie',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Estados de Reparaciones' },
            legend: { position: 'bottom' }
        }
    }
});

window.graficaClientesFrecuentes = new Chart(grafico3, {
    type: 'doughnut',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Top 10 Clientes Más Frecuentes' },
            legend: { position: 'right' }
        }
    }
});

window.graficaEmpleadosProductivos = new Chart(grafico4, {
    type: 'polarArea',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Empleados Más Productivos' },
            legend: { position: 'bottom' }
        }
    }
});

window.graficaStockCritico = new Chart(grafico5, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            title: { display: true, text: 'Stock Crítico (Menos Inventario)' },
            legend: { display: false }
        },
        scales: { x: { beginAtZero: true } }
    }
});

window.graficaServiciosReparacion = new Chart(grafico6, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Servicios de Reparación Más Solicitados' },
            legend: { display: false }
        },
        scales: { y: { beginAtZero: true } }
    }
});

window.graficaVentasMensuales = new Chart(grafico7, {
    type: 'line',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Top 10 Ventas por Cliente' },
            legend: { display: false }
        },
        scales: { y: { beginAtZero: true } }
    }
});

window.graficaCelularesMasVendidos = new Chart(grafico8, {
    type: 'bar',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        indexAxis: 'y',
        plugins: {
            title: { display: true, text: 'Top 10 Celulares Más Vendidos' },
            legend: { display: false }
        },
        scales: { x: { beginAtZero: true } }
    }
});

window.graficaReparacionesPorMarca = new Chart(grafico9, {
    type: 'pie',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Reparaciones por Marca' },
            legend: { position: 'right' }
        }
    }
});

window.graficaIngresosPorTipo = new Chart(grafico10, {
    type: 'doughnut',
    data: { labels: [], datasets: [] },
    options: {
        responsive: true,
        plugins: {
            title: { display: true, text: 'Ingresos: Ventas vs Reparaciones' },
            legend: { position: 'bottom' }
        }
    }
});

const BuscarMarcasVendidas = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarMarcasVendidasAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.marca);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaMarcasVendidas.data.labels = etiquetas;
            window.graficaMarcasVendidas.data.datasets = [{
                label: 'Cantidad Vendida',
                data: cantidades,
                backgroundColor: ['#008000', '#0000FF', '#FFFF00', '#FFA500', '#000000']
            }];
            window.graficaMarcasVendidas.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarEstadosReparaciones = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarEstadosReparacionesAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.estado);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaEstadosReparaciones.data.labels = etiquetas;
            window.graficaEstadosReparaciones.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#808080', '#FFC0CB', '#800080', '#93C5FD']
            }];
            window.graficaEstadosReparaciones.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarClientesFrecuentes = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarClientesFrecuentesAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.cliente);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaClientesFrecuentes.data.labels = etiquetas;
            window.graficaClientesFrecuentes.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#059669', '#FFFF00', '#FF0000', '#808080', '#caf0f8', '#000000', '#ECFDF5', '#F0FDF4', '#DCFCE7', '#BBF7D0']
            }];
            window.graficaClientesFrecuentes.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarEmpleadosProductivos = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarEmpleadosProductivosAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.empleado);
            const cantidades = data.map(d => parseInt(d.ventas));
            
            window.graficaEmpleadosProductivos.data.labels = etiquetas;
            window.graficaEmpleadosProductivos.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#D97706', '#008000', '#FF0000', '#FCD34D', '#FDE68A', '#FEF3C7', '#FFFBEB', '#F59E0B']
            }];
            window.graficaEmpleadosProductivos.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarStockCritico = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarStockCriticoAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.producto);
            const cantidades = data.map(d => parseInt(d.stock));
            
            window.graficaStockCritico.data.labels = etiquetas;
            window.graficaStockCritico.data.datasets = [{
                label: 'Stock Disponible',
                data: cantidades,
                backgroundColor: ['#7C3AED', '#000000', '#FF6347', '#FFFF00', '#DDD6FE', '#EDE9FE', '#F5F3FF', '#FAF5FF', '#F3F4F6', '#E5E7EB']
            }];
            window.graficaStockCritico.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarServiciosReparacion = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarServiciosReparacionAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.servicio);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaServiciosReparacion.data.labels = etiquetas;
            window.graficaServiciosReparacion.data.datasets = [{
                label: 'Cantidad de Servicios',
                data: cantidades,
                backgroundColor: ['#EC4899', '#F97316', '#EAB308', '#10B981', '#3B82F6', '#8B5CF6']
            }];
            window.graficaServiciosReparacion.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarVentasMensuales = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarVentasMensualesAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.cliente);
            const cantidades = data.map(d => parseFloat(d.total));
            
            window.graficaVentasMensuales.data.labels = etiquetas;
            window.graficaVentasMensuales.data.datasets = [{
                label: 'Total Vendido',
                data: cantidades,
                borderColor: '#0891B2',
                backgroundColor: 'rgba(8, 145, 178, 0.2)',
                tension: 0.4
            }];
            window.graficaVentasMensuales.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarCelularesMasVendidos = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarCelularesMasVendidosAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.producto);
            const cantidades = data.map(d => parseInt(d.vendidos));
            
            window.graficaCelularesMasVendidos.data.labels = etiquetas;
            window.graficaCelularesMasVendidos.data.datasets = [{
                label: 'Unidades Vendidas',
                data: cantidades,
                backgroundColor: ['#0891B2', '#000000', '#FFA500', '#A5F3FC', '#CFFAFE', '#E0F7FA', '#B2EBF2', '#80DEEA', '#4DD0E1', '#26C6DA']
            }];
            window.graficaCelularesMasVendidos.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarReparacionesPorMarca = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarReparacionesPorMarcaAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.marca);
            const cantidades = data.map(d => parseInt(d.cantidad));
            
            window.graficaReparacionesPorMarca.data.labels = etiquetas;
            window.graficaReparacionesPorMarca.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#F59E0B', '#EF4444', '#10B981', '#8B5CF6', '#06B6D4', '#EC4899', '#84CC16', '#F97316']
            }];
            window.graficaReparacionesPorMarca.update();
        }
    } catch (error) {
        console.log(error);
    }
}

const BuscarIngresosPorTipo = async () => {
    const url = '/proyecto02_macs/estadisticas/buscarIngresosPorTipoAPI';
    const config = { method: 'GET' }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;
        
        if (codigo == 1) {
            const etiquetas = data.map(d => d.tipo);
            const cantidades = data.map(d => parseFloat(d.total));
            
            window.graficaIngresosPorTipo.data.labels = etiquetas;
            window.graficaIngresosPorTipo.data.datasets = [{
                data: cantidades,
                backgroundColor: ['#059669', '#7C3AED']
            }];
            window.graficaIngresosPorTipo.update();
        }
    } catch (error) {
        console.log(error);
    }
}

BuscarMarcasVendidas();
BuscarEstadosReparaciones();
BuscarClientesFrecuentes();
BuscarEmpleadosProductivos();
BuscarStockCritico();
BuscarServiciosReparacion();
BuscarVentasMensuales();
BuscarCelularesMasVendidos();
BuscarReparacionesPorMarca();
BuscarIngresosPorTipo();