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

const guardarReparacion = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formReparacion, ['reparacion_id', 'reparacion_fecha_creacion', 'reparacion_situacion'])) {
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

            if (datatable) {
                datatable.clear().draw();
                datatable.rows.add(data).draw();
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
    columns: [
        {
            title: 'No.',
            data: 'reparacion_id',
            width: '3%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Cliente', 
            data: 'cliente_nombre',
            width: '8%'
        },
        { 
            title: 'Empleado', 
            data: 'usuario_nombre',
            width: '8%'
        },
        { 
            title: 'Marca', 
            data: 'reparacion_marca',
            width: '6%'
        },
        { 
            title: 'Modelo', 
            data: 'reparacion_tipo_celular',
            width: '8%'
        },
        { 
            title: 'Motivo', 
            data: 'reparacion_motivo',
            width: '12%'
        },
        { 
            title: 'Técnico', 
            data: 'reparacion_trabajador',
            width: '8%'
        },
        { 
            title: 'Servicio', 
            data: 'reparacion_servicio',
            width: '8%'
        },
        {
            title: 'Estado',
            data: 'reparacion_estado',
            width: '6%',
            render: (data, type, row) => {
                if (data === 'recibido') return '<span class="badge bg-warning">Recibido</span>';
                if (data === 'en_proceso') return '<span class="badge bg-info">En Proceso</span>';
                if (data === 'finalizado') return '<span class="badge bg-success">Finalizado</span>';
                return data;
            }
        },
        { 
            title: 'Precio', 
            data: 'reparacion_precio',
            width: '6%',
            render: (data, type, row) => {
                return `Q. ${parseFloat(data).toFixed(2)}`;
            }
        },
        { 
            title: 'Fecha Entrega', 
            data: 'reparacion_fecha_entrega',
            width: '7%'
        },
        { 
            title: 'Fecha Creación', 
            data: 'reparacion_fecha_creacion',
            width: '7%'
        },
        {
            title: 'Situación',
            data: 'reparacion_situacion',
            width: '5%',
            render: (data, type, row) => {
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        },
        {
            title: 'Acciones',
            data: 'reparacion_id',
            width: '8%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-cliente="${row.reparacion_cliente_id || ''}"
                         data-usuario="${row.reparacion_usuario_id || ''}"
                         data-tipo="${row.reparacion_tipo_celular || ''}"
                         data-marca="${row.reparacion_marca || ''}"
                         data-motivo="${row.reparacion_motivo || ''}"
                         data-trabajador="${row.reparacion_trabajador || ''}"
                         data-servicio="${row.reparacion_servicio || ''}"
                         data-estado="${row.reparacion_estado || ''}"
                         data-precio="${row.reparacion_precio || ''}"
                         data-fecha="${row.reparacion_fecha_entrega || ''}"
                         title="Modificar">
                         <i class='bi bi-pencil-square me-1'></i> Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}"
                         title="Eliminar">
                        <i class="bi bi-trash3 me-1"></i>Eliminar
                     </button>
                 </div>`;
            }
        }
    ]
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
    document.getElementById('reparacion_estado').value = datos.estado;
    document.getElementById('reparacion_precio').value = datos.precio;
    document.getElementById('reparacion_fecha_entrega').value = datos.fecha;

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

    if (!validarFormulario(formReparacion, ['reparacion_id', 'reparacion_fecha_creacion', 'reparacion_situacion'])) {
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

datatable.on('click', '.eliminar', EliminarReparaciones);
datatable.on('click', '.modificar', llenarFormulario);
formReparacion.addEventListener('submit', guardarReparacion);

InputPrecio.addEventListener('change', ValidarPrecio);

BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarReparacion);
BtnBuscarReparaciones.addEventListener('click', MostrarTabla);