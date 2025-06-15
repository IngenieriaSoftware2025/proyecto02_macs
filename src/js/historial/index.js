import { Dropdown } from "bootstrap";
import * as bootstrap from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formFiltros = document.getElementById('formFiltros');
const BtnBuscarHistorial = document.getElementById('BtnBuscarHistorial');
const BtnLimpiarFiltros = document.getElementById('BtnLimpiarFiltros');
const fechaInicio = document.getElementById('fecha_inicio');
const fechaFin = document.getElementById('fecha_fin');
const tipoFiltro = document.getElementById('tipo_filtro');
const seccionTabla = document.getElementById('seccionTabla');

const organizarDatosPorTipo = (data) => {
    const tipos = ['venta', 'reparacion'];
    const iconos = {
        'venta': '💰',
        'reparacion': '🔧'
    };
    const nombres = {
        'venta': 'VENTAS',
        'reparacion': 'REPARACIONES'
    };
    
    let datosOrganizados = [];
    let contador = 1;
    
    tipos.forEach(tipo => {
        const transaccionesTipo = data.filter(transaccion => transaccion.historial_tipo === tipo);
        
        if (transaccionesTipo.length > 0) {
            datosOrganizados.push({
                esSeparador: true,
                tipo: tipo,
                nombre: nombres[tipo],
                icono: iconos[tipo],
                cantidad: transaccionesTipo.length
            });
            
            transaccionesTipo.forEach(transaccion => {
                datosOrganizados.push({
                    ...transaccion,
                    numeroConsecutivo: contador++,
                    esSeparador: false
                });
            });
        }
    });
    
    return datosOrganizados;
}

const BuscarHistorial = async () => {
    const url = `/proyecto02_macs/historial/buscarAPI?fecha_inicio=${fechaInicio.value}&fecha_fin=${fechaFin.value}&tipo=${tipoFiltro.value}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            const datosOrganizados = organizarDatosPorTipo(data);

            if (datatable) {
                datatable.clear().draw();
                datatable.rows.add(datosOrganizados).draw();
            }
        } else {
            await Swal.fire({
                position: "center",
                icon: "info",
                title: "Error",
                text: mensaje,
                showConfirmButton: true,
            });
        }

    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo obtener el historial",
            showConfirmButton: true,
        });
    }
}

const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarHistorial();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableHistorial', {
    dom: `
        <"row mt-3 justify-content-between" 
            <"col" l> 
            <"col" B> 
            <"col-3" f>
        >
        t
        <"row mt-3 justify-content-between" 
            <"col-md-3 d-flex align-items-center" i> 
            <"col-md-8 d-flex justify-content-end" p>
        >
    `,
    language: lenguaje,
    data: [],
    ordering: false,
    columns: [
        {
            title: 'No.',
            data: null,
            width: '5%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) {
                    return '';
                }
                return row.numeroConsecutivo;
            }
        },
        { 
            title: 'Tipo', 
            data: 'historial_tipo',
            width: '10%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) {
                    return `<strong class="text-primary fs-5 text-center w-100 d-block">${row.icono} ${row.nombre} (${row.cantidad})</strong>`;
                }
                return data === 'venta' ? '<span class="badge bg-success">VENTA</span>' : '<span class="badge bg-info">REPARACIÓN</span>';
            }
        },
        { 
            title: 'Cliente', 
            data: 'cliente_nombre',
            width: '12%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Empleado', 
            data: 'usuario_nombre',
            width: '12%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Descripción', 
            data: 'historial_descripcion',
            width: '25%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Monto', 
            data: 'historial_monto',
            width: '10%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return `Q. ${parseFloat(data).toFixed(2)}`;
            }
        },
        { 
            title: 'Estado', 
            data: 'historial_estado',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                const estados = {
                    'completada': '<span class="badge bg-success">COMPLETADA</span>',
                    'anulada': '<span class="badge bg-danger">ANULADA</span>',
                    'entregada': '<span class="badge bg-primary">ENTREGADA</span>',
                    'recibida': '<span class="badge bg-warning">RECIBIDA</span>'
                };
                return estados[data] || data;
            }
        },
        { 
            title: 'Fecha', 
            data: 'historial_fecha_creacion',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        {
            title: 'Acciones',
            data: 'historial_id',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return `<button class='btn btn-info btn-sm ver' data-id='${data}'>Ver</button>`;
            }
        }
    ],
    rowCallback: function(row, data) {
        if (data.esSeparador) {
            row.classList.add('table-secondary');
            row.style.backgroundColor = '#f8f9fa';
            row.cells[1].colSpan = 8;
            for (let i = 2; i < 9; i++) {
                row.cells[i].style.display = 'none';
            }
        }
    }
});

const limpiarFiltros = () => {
    formFiltros.reset();
    seccionTabla.style.display = 'none';
}

const verDetalle = async (e) => {
    const historialId = e.currentTarget.dataset.id;

    try {
        const url = `/proyecto02_macs/historial/buscarDetalleAPI?historial_id=${historialId}`;
        const respuesta = await fetch(url);
        const datos = await respuesta.json();
        
        if (datos.codigo == 1) {
            const { historial, detalle } = datos;
            
            let contenidoModal = '';
            
            if (historial.historial_tipo === 'venta') {
                contenidoModal = `
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <h4>Detalles de Venta HIST-${historial.historial_id}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información de Venta</h6>
                            <p><strong>Número:</strong> HIST-${historial.historial_id}</p>
                            <p><strong>Fecha:</strong> ${historial.historial_fecha_creacion}</p>
                            <p><strong>Estado:</strong> ${historial.historial_estado.toUpperCase()}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Cliente</h6>
                            <p><strong>Nombre:</strong> ${historial.cliente_nombre}</p>
                            <p><strong>DPI:</strong> ${historial.cliente_dpi}</p>
                            <p><strong>Email:</strong> ${historial.cliente_correo}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Productos</h6>
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>Producto</th>
                                        <th>Cantidad</th>
                                        <th>Precio</th>
                                        <th>Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>`;
                
                detalle.forEach(item => {
                    contenidoModal += `
                        <tr>
                            <td>${item.marca_nombre} - ${item.celular_modelo}</td>
                            <td>${item.detalle_cantidad}</td>
                            <td>Q. ${parseFloat(item.detalle_precio_unitario).toFixed(2)}</td>
                            <td>Q. ${parseFloat(item.detalle_subtotal).toFixed(2)}</td>
                        </tr>`;
                });
                
                contenidoModal += `
                                </tbody>
                            </table>
                            <div class="text-end">
                                <h5><strong>TOTAL: Q. ${parseFloat(historial.historial_monto).toFixed(2)}</strong></h5>
                            </div>
                        </div>
                    </div>`;
            } else {
                contenidoModal = `
                    <div class="row">
                        <div class="col-md-12 text-center mb-4">
                            <h4>Detalles de Reparación HIST-${historial.historial_id}</h4>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Información de Reparación</h6>
                            <p><strong>Número:</strong> HIST-${historial.historial_id}</p>
                            <p><strong>Fecha:</strong> ${historial.historial_fecha_creacion}</p>
                            <p><strong>Estado:</strong> ${historial.historial_estado.toUpperCase()}</p>
                        </div>
                        <div class="col-md-6">
                            <h6>Cliente</h6>
                            <p><strong>Nombre:</strong> ${historial.cliente_nombre}</p>
                            <p><strong>DPI:</strong> ${historial.cliente_dpi}</p>
                            <p><strong>Email:</strong> ${historial.cliente_correo}</p>
                        </div>
                    </div>
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <h6>Detalles del Servicio</h6>
                            <p><strong>Dispositivo:</strong> ${detalle[0].reparacion_marca} ${detalle[0].reparacion_tipo_celular}</p>
                            <p><strong>Motivo:</strong> ${detalle[0].reparacion_motivo}</p>
                            <p><strong>Servicio:</strong> ${detalle[0].reparacion_servicio}</p>
                            <p><strong>Técnico:</strong> ${detalle[0].reparacion_trabajador}</p>
                            <div class="text-end">
                                <h5><strong>TOTAL: Q. ${parseFloat(historial.historial_monto).toFixed(2)}</strong></h5>
                            </div>
                        </div>
                    </div>`;
            }
            
            document.getElementById('contenidoDetalle').innerHTML = contenidoModal;
            
            const modal = new bootstrap.Modal(document.getElementById('modalDetalle'));
            modal.show();
            
        } else {
            await Swal.fire({
                position: "center",
                icon: "error",
                title: "Error",
                text: datos.mensaje,
                showConfirmButton: true,
            });
        }
    } catch (error) {
        console.log(error);
        await Swal.fire({
            position: "center",
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo obtener el detalle",
            showConfirmButton: true,
        });
    }
}

datatable.on('click', '.ver', verDetalle);
BtnBuscarHistorial.addEventListener('click', MostrarTabla);
BtnLimpiarFiltros.addEventListener('click', limpiarFiltros);