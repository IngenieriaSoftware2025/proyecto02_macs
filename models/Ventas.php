<?php

namespace Model;

use Model\ActiveRecord;

class Ventas extends ActiveRecord {
    
    public static $tabla = 'venta';
    public static $idTabla = 'venta_id';
    public static $columnasDB = 
    [
        'venta_cliente_id',
        'venta_usuario_id',
        'venta_total',
        'venta_situacion',
        //'venta_fecha_creacion'
    ];
    
    public $venta_id;
    public $venta_cliente_id;
    public $venta_usuario_id;
    public $venta_total;
    public $venta_situacion;
    public $venta_fecha_creacion;
    
    public function __construct($venta = [])
    {
        $this->venta_id = $venta['venta_id'] ?? null;
        $this->venta_cliente_id = $venta['venta_cliente_id'] ?? '';
        $this->venta_usuario_id = $venta['venta_usuario_id'] ?? '';
        $this->venta_total = $venta['venta_total'] ?? 0.00;
        $this->venta_situacion = $venta['venta_situacion'] ?? 1;
        $this->venta_fecha_creacion = $venta['venta_fecha_creacion'] ?? '';
    }

    public static function EliminarVentas($id){
        $sql = "UPDATE venta SET venta_situacion = 0 WHERE venta_id = $id";
        return self::SQL($sql);
    }

}