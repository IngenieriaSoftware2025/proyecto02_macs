<?php

namespace Model;

use Model\ActiveRecord;

class Inventario extends ActiveRecord {
    
    public static $tabla = 'inventario';
    public static $idTabla = 'inventario_id';
    public static $columnasDB = 
    [
        'inventario_celular_id',
        'inventario_cantidad',
        'inventario_precio',
        'inventario_situacion',
        //'inventario_fecha_creacion'
    ];
    
    public $inventario_id;
    public $inventario_celular_id;
    public $inventario_cantidad;
    public $inventario_precio;
    public $inventario_situacion;
    public $inventario_fecha_creacion;
    
    public function __construct($inventario = [])
    {
        $this->inventario_id = $inventario['inventario_id'] ?? null;
        $this->inventario_celular_id = $inventario['inventario_celular_id'] ?? '';
        $this->inventario_cantidad = $inventario['inventario_cantidad'] ?? 0;
        $this->inventario_precio = $inventario['inventario_precio'] ?? 0.00;
        $this->inventario_situacion = $inventario['inventario_situacion'] ?? 1;
        $this->inventario_fecha_creacion = $inventario['inventario_fecha_creacion'] ?? '';
    }

    public static function EliminarInventario($id){
        $sql = "UPDATE inventario SET inventario_situacion = 0 WHERE inventario_id = $id";
        return self::SQL($sql);
    }

}