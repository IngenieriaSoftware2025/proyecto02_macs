CREATE TABLE marca(
marca_id SERIAL PRIMARY KEY,
marca_nombre VARCHAR(100) NOT NULL,
marca_situacion SMALLINT DEFAULT 1,
marca_fecha_creacion DATE DEFAULT TODAY
);

CREATE TABLE celular(
celular_id SERIAL PRIMARY KEY,
celular_marca_id INT NOT NULL,
celular_modelo VARCHAR(100) NOT NULL,
celular_situacion SMALLINT DEFAULT 1,
celular_fecha_creacion DATE DEFAULT TODAY,
FOREIGN KEY (celular_marca_id) REFERENCES marca(marca_id)
);

CREATE TABLE usuarios1(
usuario_id SERIAL PRIMARY KEY,
usuario_nombre VARCHAR(150) NOT NULL,
usuario_dpi VARCHAR(13) NOT NULL,
usuario_telefono VARCHAR(8) NOT NULL,
usuario_correo VARCHAR(100) NOT NULL,
usuario_puesto VARCHAR(50) NOT NULL,
usuario_password VARCHAR(150),
usuario_rol VARCHAR(25) DEFAULT 'EMPLEADO',
usuario_situacion SMALLINT DEFAULT 1,
usuario_fecha_creacion DATE DEFAULT TODAY
);

CREATE TABLE cliente(
cliente_id SERIAL PRIMARY KEY,
cliente_nombre VARCHAR(150) NOT NULL,
cliente_dpi VARCHAR(13) NOT NULL,
cliente_telefono VARCHAR(8) NOT NULL,
cliente_correo VARCHAR(100) NOT NULL,
cliente_direccion VARCHAR(200) NOT NULL,
cliente_situacion SMALLINT DEFAULT 1,
cliente_fecha_creacion DATE DEFAULT TODAY
);

CREATE TABLE inventario(
inventario_id SERIAL PRIMARY KEY,
inventario_celular_id INT NOT NULL,
inventario_cantidad INT NOT NULL,
inventario_precio DECIMAL(10,2) NOT NULL,
inventario_situacion SMALLINT DEFAULT 1,
inventario_fecha_creacion DATE DEFAULT TODAY,
FOREIGN KEY (inventario_celular_id) REFERENCES celular(celular_id)
);

CREATE TABLE venta(
venta_id SERIAL PRIMARY KEY,
venta_cliente_id INT NOT NULL,
venta_usuario_id INT NOT NULL,
venta_total DECIMAL(10,2) NOT NULL,
venta_situacion SMALLINT DEFAULT 1,
venta_fecha_creacion DATE DEFAULT TODAY,
FOREIGN KEY (venta_cliente_id) REFERENCES cliente(cliente_id),
FOREIGN KEY (venta_usuario_id) REFERENCES usuarios1(usuario_id)
);

CREATE TABLE detalle_venta(
detalle_id SERIAL PRIMARY KEY,
detalle_venta_id INT NOT NULL,
detalle_inventario_id INT NOT NULL,
detalle_cantidad INT NOT NULL,
detalle_precio_unitario DECIMAL(10,2) NOT NULL,
detalle_subtotal DECIMAL(10,2) NOT NULL,
detalle_situacion SMALLINT DEFAULT 1,
FOREIGN KEY (detalle_venta_id) REFERENCES venta(venta_id),
FOREIGN KEY (detalle_inventario_id) REFERENCES inventario(inventario_id)
);

CREATE TABLE reparacion(
reparacion_id SERIAL PRIMARY KEY,
reparacion_cliente_id INT NOT NULL,
reparacion_usuario_id INT NOT NULL,
reparacion_tipo_celular VARCHAR(100) NOT NULL,
reparacion_marca VARCHAR(100) NOT NULL,
reparacion_motivo VARCHAR(250) NOT NULL,
reparacion_trabajador VARCHAR(150) NOT NULL,
reparacion_servicio VARCHAR(100) NOT NULL,
reparacion_estado VARCHAR(50) DEFAULT 'recibido',  
reparacion_precio DECIMAL(10,2) NOT NULL,
reparacion_fecha_entrega DATE,                    
reparacion_situacion SMALLINT DEFAULT 1,
reparacion_fecha_creacion DATE DEFAULT TODAY,     
FOREIGN KEY (reparacion_cliente_id) REFERENCES cliente(cliente_id),
FOREIGN KEY (reparacion_usuario_id) REFERENCES usuarios1(usuario_id)
);

CREATE TABLE historial_ventas(
historial_id SERIAL PRIMARY KEY,
historial_tipo VARCHAR(20) NOT NULL,
historial_referencia_id INT NOT NULL,
historial_cliente_id INT NOT NULL,
historial_usuario_id INT NOT NULL,
historial_descripcion VARCHAR(250) NOT NULL,
historial_monto DECIMAL(10,2) NOT NULL,
historial_estado VARCHAR(20) NOT NULL,
historial_situacion SMALLINT DEFAULT 1,
historial_fecha_creacion DATE DEFAULT TODAY
);