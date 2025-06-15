<?php

namespace Model;

use Model\ActiveRecord;

class Reparaciones extends ActiveRecord {
    
    public static $tabla = 'reparacion';
    public static $idTabla = 'reparacion_id';
    public static $columnasDB = 
    [
        'reparacion_cliente_id',
        'reparacion_usuario_id',
        'reparacion_tipo_celular',
        'reparacion_marca',
        'reparacion_motivo',
        'reparacion_trabajador',
        'reparacion_servicio',
        'reparacion_estado',
        'reparacion_precio',
        'reparacion_fecha_entrega',
        'reparacion_situacion'
    ];
    
    public $reparacion_id;
    public $reparacion_cliente_id;
    public $reparacion_usuario_id;
    public $reparacion_tipo_celular;
    public $reparacion_marca;
    public $reparacion_motivo;
    public $reparacion_trabajador;
    public $reparacion_servicio;
    public $reparacion_estado;
    public $reparacion_precio;
    public $reparacion_fecha_entrega;
    public $reparacion_situacion;
    public $reparacion_fecha_creacion;
    
    public function __construct($reparacion = [])
    {
        $this->reparacion_id = $reparacion['reparacion_id'] ?? null;
        $this->reparacion_cliente_id = $reparacion['reparacion_cliente_id'] ?? '';
        $this->reparacion_usuario_id = $reparacion['reparacion_usuario_id'] ?? '';
        $this->reparacion_tipo_celular = $reparacion['reparacion_tipo_celular'] ?? '';
        $this->reparacion_marca = $reparacion['reparacion_marca'] ?? '';
        $this->reparacion_motivo = $reparacion['reparacion_motivo'] ?? '';
        $this->reparacion_trabajador = $reparacion['reparacion_trabajador'] ?? '';
        $this->reparacion_servicio = $reparacion['reparacion_servicio'] ?? '';
        $this->reparacion_estado = $reparacion['reparacion_estado'] ?? 'recibido';
        $this->reparacion_precio = $reparacion['reparacion_precio'] ?? 0.00;
        $this->reparacion_fecha_entrega = $reparacion['reparacion_fecha_entrega'] ?? '';
        $this->reparacion_situacion = $reparacion['reparacion_situacion'] ?? 1;
        $this->reparacion_fecha_creacion = $reparacion['reparacion_fecha_creacion'] ?? null;
    }

    public static function EliminarReparaciones($id){
        $sql = "UPDATE reparacion SET reparacion_situacion = 0 WHERE reparacion_id = $id";
        return self::SQL($sql);
    }

    public static function IniciarReparacion($id){
        $sql = "UPDATE reparacion SET reparacion_estado = 'en_proceso' WHERE reparacion_id = $id AND reparacion_estado = 'recibido'";
        $resultado = self::SQL($sql);
        
        if ($resultado) {
            $sql_check = "SELECT COUNT(*) as affected FROM reparacion WHERE reparacion_id = $id AND reparacion_estado = 'en_proceso'";
            $check = self::fetchFirst($sql_check);
            return $check && $check['affected'] > 0;
        }
        
        return false;
    }

    public static function FinalizarReparacion($id){
        $sql = "UPDATE reparacion SET reparacion_estado = 'finalizado' WHERE reparacion_id = $id AND reparacion_estado = 'en_proceso'";
        $resultado = self::SQL($sql);
        
        if ($resultado) {
            $sql_check = "SELECT COUNT(*) as affected FROM reparacion WHERE reparacion_id = $id AND reparacion_estado = 'finalizado'";
            $check = self::fetchFirst($sql_check);
            return $check && $check['affected'] > 0;
        }
        
        return false;
    }

    public static function EntregarReparacion($id){
        $fechaHoy = date('m/d/Y');
        $sql = "UPDATE reparacion SET reparacion_estado = 'entregado', reparacion_fecha_entrega = '$fechaHoy' WHERE reparacion_id = $id AND reparacion_estado = 'finalizado'";
        $resultado = self::SQL($sql);
        
        if ($resultado) {
            $sql_check = "SELECT COUNT(*) as affected FROM reparacion WHERE reparacion_id = $id AND reparacion_estado = 'entregado'";
            $check = self::fetchFirst($sql_check);
            return $check && $check['affected'] > 0;
        }
        
        return false;
    }

}