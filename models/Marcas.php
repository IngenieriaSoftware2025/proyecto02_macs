<?php

namespace Model;

use Model\ActiveRecord;

class Marcas extends ActiveRecord {
    
    public static $tabla = 'marca';
    public static $idTabla = 'marca_id';
    public static $columnasDB = 
    [
        'marca_nombre',
        'marca_situacion',
        //'marca_fecha_creacion'
    ];
    
    public $marca_id;
    public $marca_nombre;
    public $marca_situacion;
    public $marca_fecha_creacion;
    
    public function __construct($marca = [])
    {
        $this->marca_id = $marca['marca_id'] ?? null;
        $this->marca_nombre = $marca['marca_nombre'] ?? '';
        $this->marca_situacion = $marca['marca_situacion'] ?? 1;
        $this->marca_fecha_creacion = $marca['marca_fecha_creacion'] ?? '';
    }

    public static function EliminarMarcas($id){
        $sql = "UPDATE marca SET marca_situacion = 0 WHERE marca_id = $id";
        return self::SQL($sql);
    }

}