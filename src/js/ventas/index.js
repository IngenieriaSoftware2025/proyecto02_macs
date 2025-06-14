import { Dropdown } from "bootstrap";
import Swal from "sweetalert2";
import { validarFormulario } from '../funciones';
import DataTable from "datatables.net-bs5";
import { lenguaje } from "../lenguaje";

const formVenta = document.getElementById('formVenta');
const BtnGuardar = document.getElementById('BtnGuardar');
const BtnModificar = document.getElementById('BtnModificar');
const BtnLimpiar = document.getElementById('BtnLimpiar');
const BtnBuscarVentas = document.getElementById('BtnBuscarVentas');
const BtnAgregarProducto = document.getElementById('BtnAgregarProducto');
const SelectCliente = document.getElementById('venta_cliente_id');
const SelectUsuario = document.getElementById('venta_usuario_id');
const SelectProducto = document.getElementById('producto_select');
const InputCantidad = document.getElementById('cantidad_input');
const cuerpoTablaProductos = document.getElementById('cuerpoTablaProductos');
const totalVenta = document.getElementById('totalVenta');
const seccionTabla = document.getElementById('seccionTabla');

let productosSeleccionados = [];
let inventarioData = [];
let inventarioOriginal = [];

const cargarClientes = async () => {
    const url = `/proyecto02_macs/ventas/buscarClientesAPI`;
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

const cargarUsuarios = async () => {
    const url = `/proyecto02_macs/ventas/buscarUsuariosAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            SelectUsuario.innerHTML = '<option value="">Seleccione un usuario</option>';
            
            data.forEach(usuario => {
                const option = document.createElement('option');
                option.value = usuario.usuario_id;
                option.textContent = usuario.usuario_nombre;
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

const cargarInventario = async () => {
    const url = `/proyecto02_macs/ventas/buscarInventarioAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            inventarioData = [...data];
            inventarioOriginal = [...data];
            actualizarDropdownProductos();
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

const actualizarDropdownProductos = () => {
    SelectProducto.innerHTML = '<option value="">Seleccione un producto</option>';
    
    inventarioData.forEach(producto => {
        const option = document.createElement('option');
        option.value = producto.inventario_id;
        option.textContent = `${producto.marca_nombre} - ${producto.celular_modelo} (Q.${producto.inventario_precio} - Stock: ${producto.inventario_cantidad})`;
        SelectProducto.appendChild(option);
    });
}

const actualizarInventarioParaModificacion = () => {
    inventarioData = [...inventarioOriginal];
    
    productosSeleccionados.forEach(productoSeleccionado => {
        const inventarioItem = inventarioData.find(item => item.inventario_id == productoSeleccionado.inventario_id);
        if (inventarioItem) {
            inventarioItem.inventario_cantidad = parseInt(inventarioItem.inventario_cantidad) + parseInt(productoSeleccionado.cantidad);
        }
    });
    
    actualizarDropdownProductos();
}

const agregarProducto = () => {
    const productoId = SelectProducto.value;
    const cantidad = parseInt(InputCantidad.value);

    if (!productoId) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Producto requerido",
            text: "Debe seleccionar un producto",
            showConfirmButton: true,
        });
        return;
    }

    if (!cantidad || cantidad < 1) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Cantidad invalida",
            text: "La cantidad debe ser mayor a 0",
            showConfirmButton: true,
        });
        return;
    }

    const producto = inventarioData.find(p => p.inventario_id == productoId);
    
    if (cantidad > producto.inventario_cantidad) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Stock insuficiente",
            text: `Solo hay ${producto.inventario_cantidad} unidades disponibles`,
            showConfirmButton: true,
        });
        return;
    }

    const productoExistente = productosSeleccionados.find(p => p.inventario_id == productoId);
    
    if (productoExistente) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Producto duplicado",
            text: "Este producto ya fue agregado",
            showConfirmButton: true,
        });
        return;
    }

    const subtotal = cantidad * parseFloat(producto.inventario_precio);

    const productoSeleccionado = {
        inventario_id: productoId,
        nombre: `${producto.marca_nombre} - ${producto.celular_modelo}`,
        precio: parseFloat(producto.inventario_precio),
        cantidad: cantidad,
        subtotal: subtotal
    };

    productosSeleccionados.push(productoSeleccionado);
    actualizarTablaProductos();
    calcularTotal();

    if (BtnModificar.classList.contains('d-none') === false) {
        actualizarInventarioParaModificacion();
    }

    SelectProducto.value = '';
    InputCantidad.value = '';
}

const actualizarTablaProductos = () => {
    cuerpoTablaProductos.innerHTML = '';

    productosSeleccionados.forEach((producto, index) => {
        const fila = document.createElement('tr');
        fila.innerHTML = `
            <td>${producto.nombre}</td>
            <td>Q. ${producto.precio.toFixed(2)}</td>
            <td>${producto.cantidad}</td>
            <td>Q. ${producto.subtotal.toFixed(2)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="eliminarProducto(${index})">
                    <i class="bi bi-trash"></i>
                </button>
            </td>
        `;
        cuerpoTablaProductos.appendChild(fila);
    });
}

const eliminarProducto = (index) => {
    productosSeleccionados.splice(index, 1);
    actualizarTablaProductos();
    calcularTotal();
    
    if (BtnModificar.classList.contains('d-none') === false) {
        actualizarInventarioParaModificacion();
    }
}

const calcularTotal = () => {
    const total = productosSeleccionados.reduce((sum, producto) => sum + producto.subtotal, 0);
    totalVenta.textContent = `Q. ${total.toFixed(2)}`;
    document.getElementById('venta_total').value = total.toFixed(2);
}

const guardarVenta = async e => {
    e.preventDefault();
    BtnGuardar.disabled = true;

    if (productosSeleccionados.length === 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Sin productos",
            text: "Debe agregar al menos un producto a la venta",
            showConfirmButton: true,
        });
        BtnGuardar.disabled = false;
        return;
    }

    if (!validarFormulario(formVenta, ['venta_id', 'venta_fecha_creacion', 'venta_situacion', 'producto_select', 'cantidad_input'])) {
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

    const body = new FormData(formVenta);
    body.append('productos_json', JSON.stringify(productosSeleccionados));
    console.log('Productos a enviar:', productosSeleccionados);
    const url = "/proyecto02_macs/ventas/guardarAPI";
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
            await cargarInventario();
            if (seccionTabla.style.display !== 'none') {
                BuscarVentas();
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
    BtnGuardar.disabled = false;
}

const BuscarVentas = async () => {
    const url = `/proyecto02_macs/ventas/buscarAPI`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos = await respuesta.json();
        const { codigo, mensaje, data } = datos;

        if (codigo == 1) {
            console.log('Ventas encontradas:', data);

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
        BuscarVentas();
    } else {
        seccionTabla.style.display = 'none';
    }
}

const datatable = new DataTable('#TableVentas', {
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
            data: 'venta_id',
            width: '5%',
            render: (data, type, row, meta) => meta.row + 1
        },
        { 
            title: 'Cliente', 
            data: 'cliente_nombre',
            width: '18%'
        },
        { 
            title: 'Usuario', 
            data: 'usuario_nombre',
            width: '13%'
        },
        { 
            title: 'Productos', 
            data: 'productos',
            width: '22%'
        },
        { 
            title: 'Cantidad', 
            data: 'cantidad_total',
            width: '8%'
        },
        { 
            title: 'Total', 
            data: 'venta_total',
            width: '10%',
            render: (data, type, row) => {
                return `Q. ${parseFloat(data).toFixed(2)}`;
            }
        },
        { 
            title: 'Fecha de Venta', 
            data: 'venta_fecha_creacion',
            width: '12%'
        },
        {
            title: 'Situación',
            data: 'venta_situacion',
            width: '7%',
            render: (data, type, row) => {
                return data == 1 ? "ACTIVO" : "ANULADO";
            }
        },
        {
            title: 'Acciones',
            data: 'venta_id',
            width: '15%',
            searchable: false,
            orderable: false,
            render: (data, type, row, meta) => {
                return `
                 <div class='d-flex justify-content-center'>
                     <button class='btn btn-warning modificar mx-1' 
                         data-id="${data}" 
                         data-cliente="${row.venta_cliente_id || ''}"
                         data-usuario="${row.venta_usuario_id || ''}"
                         data-total="${row.venta_total || ''}"
                         title="Modificar">
                         <i class='bi bi-pencil-square me-1'></i>Modificar
                     </button>
                     <button class='btn btn-danger eliminar mx-1' 
                         data-id="${data}"
                         title="Anular">
                        <i class="bi bi-x-circle me-1"></i>Anular
                     </button>
                 </div>`;
            }
        }
    ]
});

const llenarFormulario = async (event) => {
    const datos = event.currentTarget.dataset;

    document.getElementById('venta_id').value = datos.id;
    document.getElementById('venta_cliente_id').value = datos.cliente;
    document.getElementById('venta_usuario_id').value = datos.usuario;
    document.getElementById('venta_total').value = datos.total;

    const url = `/proyecto02_macs/ventas/buscarDetalleAPI?venta_id=${datos.id}`;
    const config = {
        method: 'GET'
    }

    try {
        const respuesta = await fetch(url, config);
        const datos_detalle = await respuesta.json();
        
        if (datos_detalle.codigo == 1) {
            productosSeleccionados = [];
            
            datos_detalle.data.forEach(detalle => {
                const productoSeleccionado = {
                    inventario_id: detalle.detalle_inventario_id,
                    nombre: `${detalle.marca_nombre} - ${detalle.celular_modelo}`,
                    precio: parseFloat(detalle.detalle_precio_unitario),
                    cantidad: parseInt(detalle.detalle_cantidad),
                    subtotal: parseFloat(detalle.detalle_subtotal)
                };
                productosSeleccionados.push(productoSeleccionado);
            });
            
            actualizarTablaProductos();
            calcularTotal();
            actualizarInventarioParaModificacion();
        }
    } catch (error) {
        console.log(error);
    }

    BtnGuardar.classList.add('d-none');
    BtnModificar.classList.remove('d-none');

    window.scrollTo({
        top: 0,
    });
}

const limpiarTodo = () => {
    formVenta.reset();
    productosSeleccionados = [];
    actualizarTablaProductos();
    calcularTotal();
    BtnGuardar.classList.remove('d-none');
    BtnModificar.classList.add('d-none');
    
    inventarioData = [...inventarioOriginal];
    actualizarDropdownProductos();
}

const ModificarVenta = async (event) => {
    event.preventDefault();
    BtnModificar.disabled = true;

    if (productosSeleccionados.length === 0) {
        Swal.fire({
            position: "center",
            icon: "warning",
            title: "Sin productos",
            text: "Debe agregar al menos un producto a la venta",
            showConfirmButton: true,
        });
        BtnModificar.disabled = false;
        return;
    }

    if (!validarFormulario(formVenta, ['venta_id', 'venta_fecha_creacion', 'venta_situacion', 'producto_select', 'cantidad_input'])) {
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

    const body = new FormData(formVenta);
    body.append('productos_json', JSON.stringify(productosSeleccionados));
    const url = '/proyecto02_macs/ventas/modificarAPI';
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
            await cargarInventario();
            BuscarVentas();
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

const EliminarVentas = async (e) => {
    const idVenta = e.currentTarget.dataset.id;

    const AlertaConfirmarEliminar = await Swal.fire({
        position: "center",
        icon: "info",
        title: "¿Desea anular esta venta?",
        text: 'Esta completamente seguro que desea anular esta venta',
        showConfirmButton: true,
        confirmButtonText: 'Si, Anular',
        confirmButtonColor: 'red',
        cancelButtonText: 'No, Cancelar',
        showCancelButton: true
    });

    if (AlertaConfirmarEliminar.isConfirmed) {
        const url = `/proyecto02_macs/ventas/eliminar?id=${idVenta}`;
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
                
                await cargarInventario();
                BuscarVentas();
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

window.eliminarProducto = eliminarProducto;

cargarClientes();
cargarUsuarios();
cargarInventario();

datatable.on('click', '.eliminar', EliminarVentas);
datatable.on('click', '.modificar', llenarFormulario);
formVenta.addEventListener('submit', guardarVenta);

BtnAgregarProducto.addEventListener('click', agregarProducto);
BtnLimpiar.addEventListener('click', limpiarTodo);
BtnModificar.addEventListener('click', ModificarVenta);
BtnBuscarVentas.addEventListener('click', MostrarTabla);