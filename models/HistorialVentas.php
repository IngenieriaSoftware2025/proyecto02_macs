<?php

namespace Model;

use Model\ActiveRecord;

class HistorialVentas extends ActiveRecord {
    
    public static $tabla = 'historial_ventas';
    public static $idTabla = 'historial_id';
    public static $columnasDB = 
    [
        'historial_tipo',
        'historial_referencia_id',
        'historial_cliente_id',
        'historial_usuario_id',
        'historial_descripcion',
        'historial_monto',
        'historial_estado',
        'historial_situacion'
    ];
    
    public $historial_id;
    public $historial_tipo;
    public $historial_referencia_id;
    public $historial_cliente_id;
    public $historial_usuario_id;
    public $historial_descripcion;
    public $historial_monto;
    public $historial_estado;
    public $historial_situacion;
    public $historial_fecha_creacion;
    
    public function __construct($historial = [])
    {
        $this->historial_id = $historial['historial_id'] ?? null;
        $this->historial_tipo = $historial['historial_tipo'] ?? '';
        $this->historial_referencia_id = $historial['historial_referencia_id'] ?? '';
        $this->historial_cliente_id = $historial['historial_cliente_id'] ?? '';
        $this->historial_usuario_id = $historial['historial_usuario_id'] ?? '';
        $this->historial_descripcion = $historial['historial_descripcion'] ?? '';
        $this->historial_monto = $historial['historial_monto'] ?? 0.00;
        $this->historial_estado = $historial['historial_estado'] ?? '';
        $this->historial_situacion = $historial['historial_situacion'] ?? 1;
        $this->historial_fecha_creacion = $historial['historial_fecha_creacion'] ?? null;
    }

}