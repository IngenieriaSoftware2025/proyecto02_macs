<?php

namespace Controllers;

use DateTime;
use Exception;
use MVC\Router;
use Model\ActiveRecord;
use Model\Reparaciones;
use Model\HistorialVentas;

class ReparacionesController extends ActiveRecord
{

    public static function renderizarPagina(Router $router)
    {
        $router->render('reparaciones/index', []);
    }

    public static function guardarAPI()
    {
        getHeadersApi();
    
        try {
            $_POST['reparacion_cliente_id'] = filter_var($_POST['reparacion_cliente_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($_POST['reparacion_cliente_id']) || $_POST['reparacion_cliente_id'] < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un cliente'
                ]);
                exit;
            }

            $_POST['reparacion_usuario_id'] = filter_var($_POST['reparacion_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($_POST['reparacion_usuario_id']) || $_POST['reparacion_usuario_id'] < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Debe seleccionar un usuario'
                ]);
                exit;
            }

            $_POST['reparacion_tipo_celular'] = ucwords(strtolower(trim(htmlspecialchars($_POST['reparacion_tipo_celular']))));
            
            $cantidad_tipo = strlen($_POST['reparacion_tipo_celular']);
            
            if ($cantidad_tipo < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El tipo de celular debe tener mas de 1 caracter'
                ]);
                exit;
            }

            $_POST['reparacion_marca'] = ucwords(strtolower(trim(htmlspecialchars($_POST['reparacion_marca']))));
            
            $cantidad_marca = strlen($_POST['reparacion_marca']);
            
            if ($cantidad_marca < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'La marca debe tener mas de 1 caracter'
                ]);
                exit;
            }

            $_POST['reparacion_motivo'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['reparacion_motivo']))));
            
            $cantidad_motivo = strlen($_POST['reparacion_motivo']);
            
            if ($cantidad_motivo < 5) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El motivo debe tener al menos 5 caracteres'
                ]);
                exit;
            }

            $_POST['reparacion_trabajador'] = ucwords(strtolower(trim(htmlspecialchars($_POST['reparacion_trabajador']))));
            
            $cantidad_trabajador = strlen($_POST['reparacion_trabajador']);
            
            if ($cantidad_trabajador < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El trabajador debe tener mas de 1 caracter'
                ]);
                exit;
            }

            $_POST['reparacion_servicio'] = ucwords(strtolower(trim(htmlspecialchars($_POST['reparacion_servicio']))));
            
            $cantidad_servicio = strlen($_POST['reparacion_servicio']);
            
            if ($cantidad_servicio < 2) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El servicio debe tener mas de 1 caracter'
                ]);
                exit;
            }

            $_POST['reparacion_estado'] = 'recibido';

            $_POST['reparacion_precio'] = filter_var($_POST['reparacion_precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

            if (empty($_POST['reparacion_precio']) || $_POST['reparacion_precio'] <= 0) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'El precio debe ser mayor a 0'
                ]);
                return;
            }

            unset($_POST['reparacion_fecha_entrega']);
            
            $_POST['reparacion_situacion'] = 1;
            
            unset($_POST['reparacion_fecha_creacion']);
            
            $reparacion = new Reparaciones($_POST);
            $resultado = $reparacion->crear();

            if($resultado['resultado'] == 1){
                $reparacion_id = $resultado['id'];

                $descripcion = "Reparacion: " . $_POST['reparacion_servicio'] . " - " . $_POST['reparacion_marca'] . " " . $_POST['reparacion_tipo_celular'];

                $historial_data = [
                    'historial_tipo' => 'reparacion',
                    'historial_referencia_id' => $reparacion_id,
                    'historial_cliente_id' => $_POST['reparacion_cliente_id'],
                    'historial_usuario_id' => $_POST['reparacion_usuario_id'],
                    'historial_descripcion' => $descripcion,
                    'historial_monto' => $_POST['reparacion_precio'],
                    'historial_estado' => 'recibida',
                    'historial_situacion' => 1
                ];

                $historial = new HistorialVentas($historial_data);
                $historial->crear();

                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'Reparacion registrada correctamente',
                ]);
                exit;
            } else {
                http_response_code(500);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'Error al registrar la reparacion',
                ]);
                exit;
            }
            
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error interno del servidor',
                'detalle' => $e->getMessage(),
            ]);
            exit;
        }
    }

    public static function buscarAPI()
    {
        try {
            $fecha_inicio = isset($_GET['fecha_inicio']) ? $_GET['fecha_inicio'] : null;
            $fecha_fin = isset($_GET['fecha_fin']) ? $_GET['fecha_fin'] : null;

            $condiciones = ["r.reparacion_situacion = 1"];

            if ($fecha_inicio) {
                $condiciones[] = "r.reparacion_fecha_creacion >= '{$fecha_inicio}'";
            }

            if ($fecha_fin) {
                $condiciones[] = "r.reparacion_fecha_creacion <= '{$fecha_fin}'";
            }

            $where = implode(" AND ", $condiciones);
            $sql = "SELECT r.*, c.cliente_nombre, u.usuario_nombre 
                    FROM reparacion r
                    INNER JOIN cliente c ON r.reparacion_cliente_id = c.cliente_id
                    INNER JOIN usuarios1 u ON r.reparacion_usuario_id = u.usuario_id
                    WHERE $where 
                    ORDER BY r.reparacion_fecha_creacion DESC";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Reparaciones obtenidas correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener las reparaciones',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function modificarAPI()
    {
        getHeadersApi();

        $id = $_POST['reparacion_id'];
        $_POST['reparacion_cliente_id'] = filter_var($_POST['reparacion_cliente_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($_POST['reparacion_cliente_id']) || $_POST['reparacion_cliente_id'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un cliente'
            ]);
            return;
        }

        $_POST['reparacion_usuario_id'] = filter_var($_POST['reparacion_usuario_id'], FILTER_SANITIZE_NUMBER_INT);
        
        if (empty($_POST['reparacion_usuario_id']) || $_POST['reparacion_usuario_id'] < 1) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un usuario'
            ]);
            return;
        }

        $_POST['reparacion_tipo_celular'] = ucwords(strtolower(trim(htmlspecialchars($_POST['reparacion_tipo_celular']))));

        $cantidad_tipo = strlen($_POST['reparacion_tipo_celular']);

        if ($cantidad_tipo < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El tipo de celular debe tener mas de 1 caracter'
            ]);
            return;
        }

        $_POST['reparacion_marca'] = ucwords(strtolower(trim(htmlspecialchars($_POST['reparacion_marca']))));

        $cantidad_marca = strlen($_POST['reparacion_marca']);

        if ($cantidad_marca < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'La marca debe tener mas de 1 caracter'
            ]);
            return;
        }

        $_POST['reparacion_motivo'] = ucfirst(strtolower(trim(htmlspecialchars($_POST['reparacion_motivo']))));

        $cantidad_motivo = strlen($_POST['reparacion_motivo']);

        if ($cantidad_motivo < 5) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El motivo debe tener al menos 5 caracteres'
            ]);
            return;
        }

        $_POST['reparacion_trabajador'] = ucwords(strtolower(trim(htmlspecialchars($_POST['reparacion_trabajador']))));

        $cantidad_trabajador = strlen($_POST['reparacion_trabajador']);

        if ($cantidad_trabajador < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El trabajador debe tener mas de 1 caracter'
            ]);
            return;
        }

        $_POST['reparacion_servicio'] = ucwords(strtolower(trim(htmlspecialchars($_POST['reparacion_servicio']))));

        $cantidad_servicio = strlen($_POST['reparacion_servicio']);

        if ($cantidad_servicio < 2) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El servicio debe tener mas de 1 caracter'
            ]);
            return;
        }

        $_POST['reparacion_estado'] = trim(htmlspecialchars($_POST['reparacion_estado']));

        if (empty($_POST['reparacion_estado']) || !in_array($_POST['reparacion_estado'], ['recibido', 'en_proceso', 'finalizado', 'entregado'])) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Debe seleccionar un estado valido'
            ]);
            return;
        }

        $_POST['reparacion_precio'] = filter_var($_POST['reparacion_precio'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);

        if (empty($_POST['reparacion_precio']) || $_POST['reparacion_precio'] <= 0) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'El precio debe ser mayor a 0'
            ]);
            return;
        }

        $_POST['reparacion_fecha_entrega'] = trim(htmlspecialchars($_POST['reparacion_fecha_entrega']));

        if (!empty($_POST['reparacion_fecha_entrega'])) {
            $fecha = DateTime::createFromFormat('Y-m-d', $_POST['reparacion_fecha_entrega']);
            $_POST['reparacion_fecha_entrega'] = $fecha->format('m/d/Y');
        } else {
            unset($_POST['reparacion_fecha_entrega']);
        }

        try {
            $data = Reparaciones::find($id);
            $sincronizar = [
                'reparacion_cliente_id' => $_POST['reparacion_cliente_id'],
                'reparacion_usuario_id' => $_POST['reparacion_usuario_id'],
                'reparacion_tipo_celular' => $_POST['reparacion_tipo_celular'],
                'reparacion_marca' => $_POST['reparacion_marca'],
                'reparacion_motivo' => $_POST['reparacion_motivo'],
                'reparacion_trabajador' => $_POST['reparacion_trabajador'],
                'reparacion_servicio' => $_POST['reparacion_servicio'],
                'reparacion_estado' => $_POST['reparacion_estado'],
                'reparacion_precio' => $_POST['reparacion_precio'],
                'reparacion_situacion' => 1
            ];
            
            if (isset($_POST['reparacion_fecha_entrega'])) {
                $sincronizar['reparacion_fecha_entrega'] = $_POST['reparacion_fecha_entrega'];
            }
            
            $data->sincronizar($sincronizar);
            $data->actualizar();

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La reparacion ha sido modificada exitosamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al guardar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function iniciarReparacionAPI()
    {
        getHeadersApi();
        
        try {
            $id = filter_var($_POST['reparacion_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($id) || $id < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de reparacion invalido'
                ]);
                return;
            }

            $resultado = Reparaciones::IniciarReparacion($id);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'La reparacion ha sido iniciada correctamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo iniciar la reparacion. Verifique el estado actual.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al iniciar la reparacion',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function finalizarReparacionAPI()
    {
        getHeadersApi();
        
        try {
            $id = filter_var($_POST['reparacion_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($id) || $id < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de reparacion invalido'
                ]);
                return;
            }

            $resultado = Reparaciones::FinalizarReparacion($id);

            if ($resultado) {
                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'La reparacion ha sido finalizada correctamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo finalizar la reparacion. Verifique el estado actual.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al finalizar la reparacion',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function entregarReparacionAPI()
    {
        getHeadersApi();
        
        try {
            $id = filter_var($_POST['reparacion_id'], FILTER_SANITIZE_NUMBER_INT);
            
            if (empty($id) || $id < 1) {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'ID de reparacion invalido'
                ]);
                return;
            }

            $resultado = Reparaciones::EntregarReparacion($id);

            if ($resultado) {
                $sql_reparacion = "SELECT r.*, c.cliente_nombre, u.usuario_nombre 
                                  FROM reparacion r
                                  INNER JOIN cliente c ON r.reparacion_cliente_id = c.cliente_id
                                  INNER JOIN usuarios1 u ON r.reparacion_usuario_id = u.usuario_id
                                  WHERE r.reparacion_id = $id";
                $reparacion_data = self::fetchFirst($sql_reparacion);

                if ($reparacion_data) {
                    $descripcion = "Reparacion entregada: " . $reparacion_data['reparacion_servicio'] . " - " . $reparacion_data['reparacion_marca'] . " " . $reparacion_data['reparacion_tipo_celular'];

                    $sql_actualizar_historial = "UPDATE historial_ventas 
                                                SET historial_estado = 'entregada',
                                                    historial_descripcion = '$descripcion'
                                                WHERE historial_referencia_id = $id 
                                                AND historial_tipo = 'reparacion'";
                    self::SQL($sql_actualizar_historial);
                }

                http_response_code(200);
                echo json_encode([
                    'codigo' => 1,
                    'mensaje' => 'La reparacion ha sido entregada correctamente'
                ]);
            } else {
                http_response_code(400);
                echo json_encode([
                    'codigo' => 0,
                    'mensaje' => 'No se pudo entregar la reparacion. Solo se pueden entregar reparaciones en estado Finalizado.'
                ]);
            }
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al entregar la reparacion',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function EliminarAPI()
    {
        try {
            $id = filter_var($_GET['id'], FILTER_SANITIZE_NUMBER_INT);
            
            $sql_ocultar_historial = "UPDATE historial_ventas SET historial_situacion = 0 WHERE historial_referencia_id = $id AND historial_tipo = 'reparacion'";
            self::SQL($sql_ocultar_historial);
            
            $ejecutar = Reparaciones::EliminarReparaciones($id);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'La reparacion ha sido eliminada correctamente'
            ]);
        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al eliminar',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarClientesAPI()
    {
        try {
            $sql = "SELECT cliente_id, cliente_nombre 
                    FROM cliente 
                    WHERE cliente_situacion = 1
                    ORDER BY cliente_nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Clientes obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los clientes',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarEmpleadosAPI()
    {
        try {
            $sql = "SELECT usuario_id, usuario_nombre || ' - ' || usuario_puesto as usuario_nombre
                    FROM usuarios1 
                    WHERE usuario_situacion = 1 AND usuario_puesto = 'CAJERO'
                    ORDER BY usuario_nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Empleados obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los empleados',
                'detalle' => $e->getMessage(),
            ]);
        }
    }

    public static function buscarTecnicosAPI()
    {
        try {
            $sql = "SELECT usuario_id, usuario_nombre || ' - ' || usuario_puesto as usuario_nombre
                    FROM usuarios1 
                    WHERE usuario_situacion = 1 AND usuario_puesto = 'TECNICO'
                    ORDER BY usuario_nombre";
            $data = self::fetchArray($sql);

            http_response_code(200);
            echo json_encode([
                'codigo' => 1,
                'mensaje' => 'Tecnicos obtenidos correctamente',
                'data' => $data
            ]);

        } catch (Exception $e) {
            http_response_code(400);
            echo json_encode([
                'codigo' => 0,
                'mensaje' => 'Error al obtener los tecnicos',
                'detalle' => $e->getMessage(),
            ]);
        }
    }
}