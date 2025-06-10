import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formCliente = document.getElementById('formCliente');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarClientes = document.getElementById('BtnBuscarClientes');
const InputClienteTel = document.getElementById('cliente_telefono');
const InputClienteDpi = document.getElementById('cliente_dpi');
const seccionTabla = document.getElementById('seccionTabla');

const ValidarTelefono = () => {
    const CantidadDigitos = InputClienteTel.value;

    if (CantidadDigitos.length < 1) {
        InputClienteTel.classList.remove('is-valid', 'is-invalid');
    } else {
        if (CantidadDigitos.length != 8) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Revise el numero de telefono",
                text: "La cantidad de digitos debe ser exactamente 8 digitos",
                showConfirmButton: true,
            });

            InputClienteTel.classList.remove('is-valid');
            InputClienteTel.classList.add('is-invalid');
        } else {
            InputClienteTel.classList.remove('is-invalid');
            InputClienteTel.classList.add('is-valid');
        }
    }
}

const ValidarDpi = () => {
    const dpi = InputClienteDpi.value.trim();

    if (dpi.length < 1) {
        InputClienteDpi.classList.remove('is-valid', 'is-invalid');
    } else {
        if (dpi.length != 13) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "DPI INVALIDO",
                text: "El DPI debe tener exactamente 13 caracteres",
                showConfirmButton: true,
            });

            InputClienteDpi.classList.remove('is-valid');
            InputClienteDpi.classList.add('is-invalid');
        } else {
            InputClienteDpi.classList.remove('is-invalid');
            InputClienteDpi.classList.add('is-valid');
        }
    }
}

const guardarCliente = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formCliente, ['cliente_id', 'cliente_fecha_creacion', 'cliente_situacion'])) {
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

    const body = new FormData(formCliente);
    const url = "/proyecto02_macs/clientes/guardarAPI";
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
            BuscarClientes();
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

const BuscarClientes = async () => {
    const url = `/proyecto02_macs/clientes/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Clientes encontrados:', data);

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
        BuscarClientes();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableClientes', {
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
            data: 'cliente_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Nombre', 
            data: 'cliente_nombre',
            width: '20%'
        },
        { 
            title: 'DPI', 
            data: 'cliente_dpi',
            width: '10%'
        },
        { 
            title: 'Teléfono', 
            data: 'cliente_telefono',
            width: '8%'
        },
        { 
            title: 'Correo', 
            data: 'cliente_correo',
            width: '15%'
        },
        { 
            title: 'Dirección', 
            data: 'cliente_direccion',
            width: '15%'
        },
        { 
            title: 'Fecha de Creación', 
            data: 'cliente_fecha_creacion',
            width: '10%'
        },
        {
            title: 'Situación',
            data: 'cliente_situacion',
            width: '7%',
            render: (data, type, row) => {
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        },
        {
            title: 'Acciones',
            data: 'cliente_id',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nombre="${row.cliente_nombre || ''}"
                         data-dpi="${row.cliente_dpi || ''}"
                         data-telefono="${row.cliente_telefono || ''}"
                         data-correo="${row.cliente_correo || ''}"
                         data-direccion="${row.cliente_direccion || ''}"
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

    document.getElementById('cliente_id').value = datos.id;
    document.getElementById('cliente_nombre').value = datos.nombre;
    document.getElementById('cliente_dpi').value = datos.dpi;
    document.getElementById('cliente_telefono').value = datos.telefono;
    document.getElementById('cliente_correo').value = datos.correo;
    document.getElementById('cliente_direccion').value = datos.direccion;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    formCliente.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarCliente = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formCliente, ['cliente_id', 'cliente_fecha_creacion', 'cliente_situacion'])) {
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

    const body = new FormData(formCliente);
    const url = '/proyecto02_macs/clientes/modificarAPI';
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
            BuscarClientes();
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

const EliminarClientes = async (e) => {
    const idCliente = e.currentTarget.dataset.id;

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
        const url = `/proyecto02_macs/clientes/eliminar?id=${idCliente}`;
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
                
                BuscarClientes();
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

datatable.on('click', '.eliminar', EliminarClientes);
datatable.on('click', '.modificar', llenarFormulario);
formCliente.addEventListener('submit', guardarCliente);

InputClienteTel.addEventListener('change', ValidarTelefono);
InputClienteDpi.addEventListener('change', ValidarDpi);

BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarCliente);
BtnBuscarClientes.addEventListener('click', MostrarTabla);