<?php

namespace Model;

use Model\ActiveRecord;

class DetalleVenta extends ActiveRecord {
    
    public static $tabla = 'detalle_venta';
    public static $idTabla = 'detalle_id';
    public static $columnasDB = 
    [
        'detalle_venta_id',
        'detalle_inventario_id',
        'detalle_cantidad',
        'detalle_precio_unitario',
        'detalle_subtotal',
        'detalle_situacion'
    ];
    
    public $detalle_id;
    public $detalle_venta_id;
    public $detalle_inventario_id;
    public $detalle_cantidad;
    public $detalle_precio_unitario;
    public $detalle_subtotal;
    public $detalle_situacion;
    
    public function __construct($detalle = [])
    {
        $this->detalle_id = $detalle['detalle_id'] ?? null;
        $this->detalle_venta_id = $detalle['detalle_venta_id'] ?? '';
        $this->detalle_inventario_id = $detalle['detalle_inventario_id'] ?? '';
        $this->detalle_cantidad = $detalle['detalle_cantidad'] ?? 0;
        $this->detalle_precio_unitario = $detalle['detalle_precio_unitario'] ?? 0.00;
        $this->detalle_subtotal = $detalle['detalle_subtotal'] ?? 0.00;
        $this->detalle_situacion = $detalle['detalle_situacion'] ?? 1;
    }

}