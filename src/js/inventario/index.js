import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formInventario = document.getElementById('formInventario');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarInventario = document.getElementById('BtnBuscarInventario');
const InputInventarioCantidad = document.getElementById('inventario_cantidad');
const InputInventarioPrecio = document.getElementById('inventario_precio');
const SelectCelular = document.getElementById('inventario_celular_id');
const seccionTabla = document.getElementById('seccionTabla');

const ValidarCantidad = () => {
    const cantidad = InputInventarioCantidad.value;

    if (cantidad.length < 1) {
        InputInventarioCantidad.classList.remove('is-valid', 'is-invalid');
    } else {
        if (cantidad < 1) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Cantidad invalida",
                text: "La cantidad debe ser mayor a 0",
                showConfirmButton: true,
            });

            InputInventarioCantidad.classList.remove('is-valid');
            InputInventarioCantidad.classList.add('is-invalid');
        } else {
            InputInventarioCantidad.classList.remove('is-invalid');
            InputInventarioCantidad.classList.add('is-valid');
        }
    }
}

const ValidarPrecio = () => {
    const precio = InputInventarioPrecio.value;

    if (precio.length < 1) {
        InputInventarioPrecio.classList.remove('is-valid', 'is-invalid');
    } else {
        if (precio <= 0) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Precio invalido",
                text: "El precio debe ser mayor a 0",
                showConfirmButton: true,
            });

            InputInventarioPrecio.classList.remove('is-valid');
            InputInventarioPrecio.classList.add('is-invalid');
        } else {
            InputInventarioPrecio.classList.remove('is-invalid');
            InputInventarioPrecio.classList.add('is-valid');
        }
    }
}

const cargarCelulares = async () => {
    const url = `/proyecto02_macs/inventario/buscarCelularesAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectCelular.innerHTML = '<option value="">Seleccione un celular</option>';
            
            data.forEach(celular => {
                const option = document.createElement('option');
                option.value = celular.celular_id;
                option.textContent = `${celular.marca_nombre} - ${celular.celular_modelo}`;
                SelectCelular.appendChild(option);
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

const guardarInventario = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formInventario, ['inventario_id', 'inventario_fecha_creacion', 'inventario_situacion'])) {
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

    const body = new FormData(formInventario);
    const url = "/proyecto02_macs/inventario/guardarAPI";
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
            BuscarInventario();
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

const BuscarInventario = async () => {
    const url = `/proyecto02_macs/inventario/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Inventario encontrado:', data);

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
        BuscarInventario();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableInventario', {
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
            data: 'inventario_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Marca', 
            data: 'marca_nombre',
            width: '15%'
        },
        { 
            title: 'Modelo', 
            data: 'celular_modelo',
            width: '20%'
        },
        { 
            title: 'Cantidad', 
            data: 'inventario_cantidad',
            width: '10%'
        },
        { 
            title: 'Precio', 
            data: 'inventario_precio',
            width: '12%',
            render: (data, type, row) => {
                return `Q. ${parseFloat(data).toFixed(2)}`;
            }
        },
        { 
            title: 'Fecha de Creación', 
            data: 'inventario_fecha_creacion',
            width: '15%'
        },
        {
            title: 'Situación',
            data: 'inventario_situacion',
            width: '8%',
            render: (data, type, row) => {
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        },
        {
            title: 'Acciones',
            data: 'inventario_id',
            width: '15%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-celular="${row.inventario_celular_id || ''}"
                         data-cantidad="${row.inventario_cantidad || ''}"
                         data-precio="${row.inventario_precio || ''}"
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

    document.getElementById('inventario_id').value = datos.id;
    document.getElementById('inventario_celular_id').value = datos.celular;
    document.getElementById('inventario_cantidad').value = datos.cantidad;
    document.getElementById('inventario_precio').value = datos.precio;

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    formInventario.reset();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarInventario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formInventario, ['inventario_id', 'inventario_fecha_creacion', 'inventario_situacion'])) {
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

    const body = new FormData(formInventario);
    const url = '/proyecto02_macs/inventario/modificarAPI';
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
            BuscarInventario();
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

const EliminarInventario = async (e) => {
    const idInventario = e.currentTarget.dataset.id;

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
        const url = `/proyecto02_macs/inventario/eliminar?id=${idInventario}`;
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
                
                BuscarInventario();
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

cargarCelulares();

datatable.on('click', '.eliminar', EliminarInventario);
datatable.on('click', '.modificar', llenarFormulario);
formInventario.addEventListener('submit', guardarInventario);

InputInventarioCantidad.addEventListener('change', ValidarCantidad);
InputInventarioPrecio.addEventListener('change', ValidarPrecio);

BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarInventario);
BtnBuscarInventario.addEventListener('click', MostrarTabla);