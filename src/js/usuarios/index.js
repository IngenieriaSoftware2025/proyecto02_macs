import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formUsuario = document.getElementById('formUsuario');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarUsuarios = document.getElementById('BtnBuscarUsuarios');
const InputUsuarioTel = document.getElementById('usuario_telefono');
const InputUsuarioDpi = document.getElementById('usuario_dpi');
const seccionTabla = document.getElementById('seccionTabla');

const ValidarTelefono = () => {
    const CantidadDigitos = InputUsuarioTel.value;

    if (CantidadDigitos.length < 1) {
        InputUsuarioTel.classList.remove('is-valid', 'is-invalid');
    } else {
        if (CantidadDigitos.length != 8) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "Revise el numero de telefono",
                text: "La cantidad de digitos debe ser exactamente 8 digitos",
                showConfirmButton: true,
            });

            InputUsuarioTel.classList.remove('is-valid');
            InputUsuarioTel.classList.add('is-invalid');
        } else {
            InputUsuarioTel.classList.remove('is-invalid');
            InputUsuarioTel.classList.add('is-valid');
        }
    }
}

const ValidarDpi = () => {
    const dpi = InputUsuarioDpi.value.trim();

    if (dpi.length < 1) {
        InputUsuarioDpi.classList.remove('is-valid', 'is-invalid');
    } else {
        if (dpi.length != 13) {
            Swal.fire({
                position: "center",
                icon: "error",
                title: "DPI INVALIDO",
                text: "El DPI debe tener exactamente 13 caracteres",
                showConfirmButton: true,
            });

            InputUsuarioDpi.classList.remove('is-valid');
            InputUsuarioDpi.classList.add('is-invalid');
        } else {
            InputUsuarioDpi.classList.remove('is-invalid');
            InputUsuarioDpi.classList.add('is-valid');
        }
    }
}

const guardarUsuario = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (!validarFormulario(formUsuario, ['usuario_id', 'usuario_fecha_creacion', 'usuario_situacion'])) {
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

    const body = new FormData(formUsuario);
    const url = "/proyecto02_macs/usuarios/guardarAPI";
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
            BuscarUsuarios();
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

const organizarDatosPorPuesto = (data) => {
    const puestos = ['GERENTE', 'SUPERVISOR', 'TECNICO', 'VENDEDOR', 'CAJERO'];
    const iconos = {
        'GERENTE': '👔',
        'SUPERVISOR': '👁️',
        'TECNICO': '🔧',
        'VENDEDOR': '💰',
        'CAJERO': '💳'
    };
    
    let datosOrganizados = [];
    let contador = 1;
    
    puestos.forEach(puesto => {
        const usuariosPuesto = data.filter(user => user.usuario_puesto === puesto);
        
        if (usuariosPuesto.length > 0) {
            datosOrganizados.push({
                esSeparador: true,
                puesto: puesto,
                icono: iconos[puesto],
                cantidad: usuariosPuesto.length
            });
            
            usuariosPuesto.forEach(usuario => {
                datosOrganizados.push({
                    ...usuario,
                    numeroConsecutivo: contador++,
                    esSeparador: false
                });
            });
        }
    });
    
    return datosOrganizados;
}

const BuscarUsuarios = async () => {
    const url = `/proyecto02_macs/usuarios/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Usuarios encontrados:', data);
            
            const datosOrganizados = organizarDatosPorPuesto(data);

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

const MostrarTabla = () => {
    if (seccionTabla.style.display === 'none') {
        seccionTabla.style.display = 'block';
        BuscarUsuarios();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableUsuarios', {
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
            title: 'Nombre', 
            data: 'usuario_nombre',
            width: '18%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) {
                    return `<strong class="text-primary fs-5 text-center w-100 d-block">${row.puesto} (${row.cantidad})</strong>`;
                }
                return data;
            }
        },
        { 
            title: 'DPI', 
            data: 'usuario_dpi',
            width: '10%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Teléfono', 
            data: 'usuario_telefono',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Correo', 
            data: 'usuario_correo',
            width: '13%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Puesto', 
            data: 'usuario_puesto',
            width: '10%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Rol', 
            data: 'usuario_rol',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        { 
            title: 'Fecha de Creación', 
            data: 'usuario_fecha_creacion',
            width: '10%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data;
            }
        },
        {
            title: 'Situación',
            data: 'usuario_situacion',
            width: '8%',
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return data == 1 ? "ACTIVO" : "INACTIVO";
            }
        },
        {
            title: 'Acciones',
            data: 'usuario_id',
            width: '10%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                if (row.esSeparador) return '';
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-nombre="${row.usuario_nombre || ''}"
                         data-dpi="${row.usuario_dpi || ''}"
                         data-telefono="${row.usuario_telefono || ''}"
                         data-correo="${row.usuario_correo || ''}"
                         data-puesto="${row.usuario_puesto || ''}"
                         data-rol="${row.usuario_rol || ''}"
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
    ],
    rowCallback: function(row, data) {
        if (data.esSeparador) {
            row.classList.add('table-secondary');
            row.style.backgroundColor = '#f8f9fa';
            row.cells[1].colSpan = 9;
            for (let i = 2; i < row.cells.length; i++) {
                row.cells[i].style.display = 'none';
            }
        }
    }
});

const llenarFormulario = (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('usuario_id').value = datos.id;
    document.getElementById('usuario_nombre').value = datos.nombre;
    document.getElementById('usuario_dpi').value = datos.dpi;
    document.getElementById('usuario_telefono').value = datos.telefono;
    document.getElementById('usuario_correo').value = datos.correo;
    document.getElementById('usuario_puesto').value = datos.puesto;
    document.getElementById('usuario_rol').value = datos.rol;
    document.getElementById('usuario_password').value = '';
    document.getElementById('usuario_password').placeholder = 'Dejar vacío para mantener contraseña actual';
    document.getElementById('usuario_password').removeAttribute('required');

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    formUsuario.reset();
    document.getElementById('usuario_password').placeholder = 'Ingrese contraseña (mínimo 6 caracteres)';
    document.getElementById('usuario_password').setAttribute('required', '');
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
}

const ModificarUsuario = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (!validarFormulario(formUsuario, ['usuario_id', 'usuario_fecha_creacion', 'usuario_situacion', 'usuario_password'])) {
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

    const body = new FormData(formUsuario);
    const url = '/proyecto02_macs/usuarios/modificarAPI';
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
            BuscarUsuarios();
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

const EliminarUsuarios = async (e) => {
    const idUsuario = e.currentTarget.dataset.id;

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
        const url = `/proyecto02_macs/usuarios/eliminar?id=${idUsuario}`;
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
                
                BuscarUsuarios();
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

datatable.on('click', '.eliminar', EliminarUsuarios);
datatable.on('click', '.modificar', llenarFormulario);
formUsuario.addEventListener('submit', guardarUsuario);

InputUsuarioTel.addEventListener('change', ValidarTelefono);
InputUsuarioDpi.addEventListener('change', ValidarDpi);

BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarUsuario);
BtnBuscarUsuarios.addEventListener('click', MostrarTabla);