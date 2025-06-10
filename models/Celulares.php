<?php

namespace Model;

use Model\ActiveRecord;

class Celulares extends ActiveRecord {
    
    public static $tabla = 'celular';
    public static $idTabla = 'celular_id';
    public static $columnasDB = 
    [
        'celular_marca_id',
        'celular_modelo',
        'celular_situacion',
        //'celular_fecha_creacion'
    ];
    
    public $celular_id;
    public $celular_marca_id;
    public $celular_modelo;
    public $celular_situacion;
    public $celular_fecha_creacion;
    
    public function __construct($celular = [])
    {
        $this->celular_id = $celular['celular_id'] ?? null;
        $this->celular_marca_id = $celular['celular_marca_id'] ?? '';
        $this->celular_modelo = $celular['celular_modelo'] ?? '';
        $this->celular_situacion = $celular['celular_situacion'] ?? 1;
        $this->celular_fecha_creacion = $celular['celular_fecha_creacion'] ?? '';
    }

    public static function EliminarCelulares($id){
        $sql = "UPDATE celular SET celular_situacion = 0 WHERE celular_id = $id";
        return self::SQL($sql);
    }

}