import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formReparacion = document.getElementById('formReparacion');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarReparaciones = document.getElementById('BtnBuscarReparaciones');
const SelectCliente = document.getElementById('reparacion_cliente_id');
const SelectUsuario = document.getElementById('reparacion_usuario_id');
const InputMarca = document.getElementById('reparacion_marca');
const InputTipoCelular = document.getElementById('reparacion_tipo_celular');
const SelectTrabajador = document.getElementById('reparacion_trabajador');
const InputPrecio = document.getElementById('reparacion_precio');
const seccionTabla = document.getElementById('seccionTabla');

const ValidarPrecio = () => {
    const precio = InputPrecio.value;

    if (precio.length < 1) {
        InputPrecio.classList.remove('is-valid', 'is-invalid');
    } else {
        if (precio <= 0) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Precio invalido",
                text: "El precio debe ser mayor a 0",
                showConfirmButton: true,
            });

            InputPrecio.classList.remove('is-valid');
            InputPrecio.classList.add('is-invalid');
        } else {
            InputPrecio.classList.remove('is-invalid');
            InputPrecio.classList.add('is-valid');
        }
    }
}

const cargarClientes = async () => {
    const url = `/proyecto02_macs/reparaciones/buscarClientesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectCliente.innerHTML = '<option value="">Seleccione un cliente</option>';
            
            data.forEach(cliente => {
                const option = document.createElement('option');
                option.value = cliente.cliente_id;
                option.textContent = cliente.cliente_nombre;
                SelectCliente.appendChild(option);
            });
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
    }
}

const cargarEmpleados = async () => {
    const url = `/proyecto02_macs/reparaciones/buscarEmpleadosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione un empleado</option>';
            
            data.forEach(empleado => {
                const option = document.createElement('option');
                option.value = empleado.usuario_id;
                option.textContent = empleado.usuario_nombre;
                SelectUsuario.appendChild(option);
            });
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
    }
}

const cargarTecnicos = async () => {
    const url = `/proyecto02_macs/reparaciones/buscarTecnicosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectTrabajador.innerHTML = '<option value="">Seleccione un técnico</option>';
            
            data.forEach(tecnico => {
                const option = document.createElement('option');
                option.value = tecnico.usuario_nombre;
                option.textContent = tecnico.usuario_nombre;
                SelectTrabajador.appendChild(option);
            });
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
    }
}

const organizarDatosPorEstado = (data) => {
    const estados = ['recibido', 'en_proceso', 'finalizado', 'entregado'];
    const iconos = {
        'recibido': '',
        'en_proceso': '', 
        'finalizado': '',
        'entregado': ''
    };
    const nombres = {
        'recibido': 'RECIBIDO',
        'en_proceso': 'EN PROCESO',
        'finalizado': 'FINALIZADO', 
        'entregado': 'ENTREGADO'
    };
    
    let datosOrganizados = [];
    let contador = 1;
    
    estados.forEach(estado => {
        const reparacionesEstado = data.filter(reparacion => reparacion.reparacion_estado === estado);
        
        if (reparacionesEstado.length > 0) {
            datosOrganizados.push({
                esSeparador: true,
                estado: estado,
                nombre: nombres[estado],
                icono: iconos[estado],
                cantidad: reparacionesEstado.length
            });
            
            reparacionesEstado.forEach(reparacion => {
                datosOrganizados.push({
                    ...reparacion,
                    numeroConsecutivo: contador++,
                    esSeparador: false
                });
            });
        }
    });
    
    return datosOrganizados;
}

const guardarReparacion = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formReparacion, ['reparacion_id', 'reparacion_fecha_creacion', 'reparacion_situacion', 'reparacion_estado'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    const body = new FormData(formReparacion);
    const url = "/proyecto02_macs/reparaciones/guardarAPI";
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        console.log(datos);
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarReparaciones();
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
    }
    BtnGuardar.disabled = false;
}

const BuscarReparaciones = async () => {
    const url = `/proyecto02_macs/reparaciones/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Reparaciones encontradas:', data);
            
            const datosOrganizados = organizarDatosPorEstado(data);

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
    }
}

const iniciarReparacion = async (e) => {
    const idReparacion = e.currentTarget.dataset.id;

    const AlertaConfirmar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Iniciar reparación?",
        text: 'Se iniciará con el proceso de reparación del dispositivo',
        showConfirmButton: true,
        confirmButtonText: 'Si, Iniciar',
        confirmButtonColor: '#28a745',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmar.isConfirmed) {
        const body = new FormData();
        body.append('reparacion_id', idReparacion);
        
        const url = `/proyecto02_macs/reparaciones/iniciarAPI`;
        const config = {
            method: 'POST',
            body
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarReparaciones();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error);
        }
    }
}

const finalizarReparacion = async (e) => {
    const idReparacion = e.currentTarget.dataset.id;

    const AlertaConfirmar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Finalizar reparación?",
        text: 'Se finalizará con el proceso de reparación del dispositivo',
        showConfirmButton: true,
        confirmButtonText: 'Si, Finalizar',
        confirmButtonColor: '#007bff',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmar.isConfirmed) {
        const body = new FormData();
        body.append('reparacion_id', idReparacion);
        
        const url = `/proyecto02_macs/reparaciones/finalizarAPI`;
        const config = {
            method: 'POST',
            body
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarReparaciones();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error);
        }
    }
}

const entregarReparacion = async (e) => {
    const idReparacion = e.currentTarget.dataset.id;

    const AlertaConfirmar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Entregar reparación?",
        text: 'Se entregará su dispositivo en buen estado y completamente funcional',
        showConfirmButton: true,
        confirmButtonText: 'Si, Entregar',
        confirmButtonColor: '#ffc107',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmar.isConfirmed) {
        const body = new FormData();
        body.append('reparacion_id', idReparacion);
        
        const url = `/proyecto02_macs/reparaciones/entregarAPI`;
        const config = {
            method: 'POST',
            body
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarReparaciones();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
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
                title: "Error",
                text: "Error de conexión. Intente nuevamente.",
                showConfirmButton: true,
            });
        }
    }
}

const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarReparaciones();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableReparaciones', {
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
            width: '3%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) {
                    return '';
                }
                return row.numeroConsecutivo;
            }
        },
        { 
            title: 'Cliente', 
            data: 'cliente_nombre',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) {
                    return `<strong class="text-primary fs-5 text-center w-100 d-block">${row.icono} ${row.nombre} (${row.cantidad})</strong>`;
                }
                return data;
            }
        },
        { 
            title: 'Empleado', 
            data: 'usuario_nombre',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Marca', 
            data: 'reparacion_marca',
            width: '6%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Modelo', 
            data: 'reparacion_tipo_celular',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Motivo', 
            data: 'reparacion_motivo',
            width: '12%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Técnico', 
            data: 'reparacion_trabajador',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Servicio', 
            data: 'reparacion_servicio',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Precio', 
            data: 'reparacion_precio',
            width: '6%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return `Q. ${parseFloat(data).toFixed(2)}`;
            }
        },
        { 
            title: 'Fecha Entrega', 
            data: 'reparacion_fecha_entrega',
            width: '7%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data || 'Pendiente';
            }
        },
        { 
            title: 'Fecha Recibido', 
            data: 'reparacion_fecha_creacion',
            width: '7%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        {
            title: 'Estado',
            data: 'reparacion_estado',
            width: '5%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                
                const estados = {
                    'recibido': '<span class="badge bg-warning text-dark">RECIBIDO</span>',
                    'en_proceso': '<span class="badge bg-info text-white">EN PROCESO</span>',
                    'finalizado': '<span class="badge bg-success">FINALIZADO</span>',
                    'entregado': '<span class="badge bg-primary">ENTREGADO</span>'
                };
                
                return estados[data] || data;
            }
        },
        {
            title: 'Acciones',
            data: 'reparacion_id',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                
                const estado = row.reparacion_estado;
                let botones = '';

                if (estado === 'recibido') {
                    botones = `
                        <button class='btn btn-success btn-sm iniciar mx-1' 
                            data-id="${data}" 
                            title="Iniciar Reparación">
                            <i class='bi bi-play-fill'></i> Iniciar
                        </button>
                        <button class='btn btn-warning btn-sm modificar mx-1' 
                            data-id="${data}" 
                            data-cliente="${row.reparacion_cliente_id || ''}"
                            data-usuario="${row.reparacion_usuario_id || ''}"
                            data-tipo="${row.reparacion_tipo_celular || ''}"
                            data-marca="${row.reparacion_marca || ''}"
                            data-motivo="${row.reparacion_motivo || ''}"
                            data-trabajador="${row.reparacion_trabajador || ''}"
                            data-servicio="${row.reparacion_servicio || ''}"
                            data-precio="${row.reparacion_precio || ''}"
                            title="Modificar">
                            <i class='bi bi-pencil'></i>
                        </button>
                        <button class='btn btn-danger btn-sm eliminar mx-1' 
                            data-id="${data}"
                            title="Eliminar">
                            <i class="bi bi-trash3"></i> Eliminar
                        </button>
                    `;
                }
                else if (estado === 'en_proceso') {
                    botones = `
                        <button class='btn btn-primary btn-sm finalizar mx-1' 
                            data-id="${data}" 
                            title="Finalizar Reparación">
                            <i class='bi bi-check-circle'></i> Finalizar
                        </button>
                    `;
                }
                else if (estado === 'finalizado') {
                    botones = `
                        <button class='btn btn-warning btn-sm entregar mx-1' 
                            data-id="${data}" 
                            title="Entregar">
                            <i class='bi bi-box-seam'></i> Entregar
                        </button>
                    `;
                }
                else if (estado === 'entregado') {
                    botones = `
                        <button class='btn btn-danger btn-sm eliminar mx-1' 
                            data-id="${data}"
                            title="Eliminar">
                            <i class="bi bi-trash3"></i> Eliminar
                        </button>
                    `;
                }

                return `<div class='d-flex justify-content-center'>${botones}</div>`;
            }
        }
    ],
    rowCallback: function(row, data) {
        if (data.esSeparador) {
            row.classList.add('table-secondary');
            row.style.backgroundColor = '#f8f9fa';
            row.cells[1].colSpan = 12;
            for (let i = 2; i < row.cells.length; i++) {
                row.cells[i].style.display = 'none';
            }
        }
    }
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('reparacion_id').value = datos.id;
    document.getElementById('reparacion_cliente_id').value = datos.cliente;
    document.getElementById('reparacion_usuario_id').value = datos.usuario;
    document.getElementById('reparacion_tipo_celular').value = datos.tipo;
    document.getElementById('reparacion_marca').value = datos.marca;
    document.getElementById('reparacion_motivo').value = datos.motivo;
    document.getElementById('reparacion_trabajador').value = datos.trabajador;
    document.getElementById('reparacion_servicio').value = datos.servicio;
    document.getElementById('reparacion_precio').value = datos.precio;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    formReparacion.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarReparacion = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formReparacion, ['reparacion_id', 'reparacion_fecha_creacion', 'reparacion_situacion', 'reparacion_estado'])) {
        Swal.fire({
            position: "center",
            icon: "info",
            title: "FORMULARIO INCOMPLETO",
            text: "Debe de validar todos los campos",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    const body = new FormData(formReparacion);
    const url = '/proyecto02_macs/reparaciones/modificarAPI';
    const config = {
        method: 'POST',
        body
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje } = datos;

        if (codigo == 1) {
            await Swal.fire({
                position: "center",
                icon: "success",
                title: "Exito",
                text: mensaje,
                showConfirmButton: true,
            });

            limpiarTodo();
            BuscarReparaciones();
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
    }
    BtnModificar.disabled = false;
}

const EliminarReparaciones = async (e) => {
    const idReparacion = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea ejecutar esta acción?",
        text: 'Esta completamente seguro que desea eliminar este registro',
        showConfirmButton: true,
        confirmButtonText: 'Si, Eliminar',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto02_macs/reparaciones/eliminar?id=${idReparacion}`;
        const config = {
            method: 'GET'
        }

        try {
            const consulta = await fetch(url, config);
            const respuesta = await consulta.json();
            const { codigo, mensaje } = respuesta;

            if (codigo == 1) {
                await Swal.fire({
                    position: "center",
                    icon: "success",
                    title: "Exito",
                    text: mensaje,
                    showConfirmButton: true,
                });
                
                BuscarReparaciones();
            } else {
                await Swal.fire({
                    position: "center",
                    icon: "error",
                    title: "Error",
                    text: mensaje,
                    showConfirmButton: true,
                });
            }

        } catch (error) {
            console.log(error);
        }
    }
}

cargarClientes();
cargarEmpleados();
cargarTecnicos();

datatable.on('click', '.iniciar', iniciarReparacion);
datatable.on('click', '.finalizar', finalizarReparacion);
datatable.on('click', '.entregar', entregarReparacion);
datatable.on('click', '.eliminar', EliminarReparaciones);
datatable.on('click', '.modificar', llenarFormulario);

formReparacion.addEventListener('submit', guardarReparacion);
InputPrecio.addEventListener('change', ValidarPrecio);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarReparacion);
BtnBuscarReparaciones.addEventListener('click', MostrarTabla);