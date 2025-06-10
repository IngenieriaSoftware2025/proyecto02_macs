<?php

namespace Model;

use Model\ActiveRecord;

class Clientes extends ActiveRecord {
    
    public static $tabla = 'cliente';
    public static $idTabla = 'cliente_id';
    public static $columnasDB = 
    [
        'cliente_nombre',
        'cliente_dpi',
        'cliente_telefono',
        'cliente_correo',
        'cliente_direccion',
        'cliente_situacion',
        //'cliente_fecha_creacion'
    ];
    
    public $cliente_id;
    public $cliente_nombre;
    public $cliente_dpi;
    public $cliente_telefono;
    public $cliente_correo;
    public $cliente_direccion;
    public $cliente_situacion;
    public $cliente_fecha_creacion;
    
    public function __construct($cliente = [])
    {
        $this->cliente_id = $cliente['cliente_id'] ?? null;
        $this->cliente_nombre = $cliente['cliente_nombre'] ?? '';
        $this->cliente_dpi = $cliente['cliente_dpi'] ?? '';
        $this->cliente_telefono = $cliente['cliente_telefono'] ?? '';
        $this->cliente_correo = $cliente['cliente_correo'] ?? '';
        $this->cliente_direccion = $cliente['cliente_direccion'] ?? '';
        $this->cliente_situacion = $cliente['cliente_situacion'] ?? 1;
        $this->cliente_fecha_creacion = $cliente['cliente_fecha_creacion'] ?? '';
    }

    public static function EliminarClientes($id){
        $sql = "UPDATE cliente SET cliente_situacion = 0 WHERE cliente_id = $id";
        return self::SQL($sql);
    }

}