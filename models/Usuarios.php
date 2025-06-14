<?php

namespace Model;

use Model\ActiveRecord;

class Usuarios extends ActiveRecord {
    
    public static $tabla = 'usuarios1';
    public static $idTabla = 'usuario_id';
    public static $columnasDB = 
    [
        'usuario_nombre',
        'usuario_dpi',
        'usuario_telefono',
        'usuario_correo',
        'usuario_puesto',
        'usuario_password',
        'usuario_rol',
        'usuario_situacion',
        //'usuario_fecha_creacion'
    ];
    
    public $usuario_id;
    public $usuario_nombre;
    public $usuario_dpi;
    public $usuario_telefono;
    public $usuario_correo;
    public $usuario_puesto;
    public $usuario_password;
    public $usuario_rol;
    public $usuario_situacion;
    public $usuario_fecha_creacion;
    
    public function __construct($usuario = [])
    {
        $this->usuario_id = $usuario['usuario_id'] ?? null;
        $this->usuario_nombre = $usuario['usuario_nombre'] ?? '';
        $this->usuario_dpi = $usuario['usuario_dpi'] ?? '';
        $this->usuario_telefono = $usuario['usuario_telefono'] ?? '';
        $this->usuario_correo = $usuario['usuario_correo'] ?? '';
        $this->usuario_puesto = $usuario['usuario_puesto'] ?? '';
        $this->usuario_password = $usuario['usuario_password'] ?? '';
        $this->usuario_rol = $usuario['usuario_rol'] ?? 'EMPLEADO';
        $this->usuario_situacion = $usuario['usuario_situacion'] ?? 1;
        $this->usuario_fecha_creacion = $usuario['usuario_fecha_creacion'] ?? '';
    }

    public static function EliminarUsuarios($id){
        $sql = "UPDATE usuarios1 SET usuario_situacion = 0 WHERE usuario_id = $id";
        return self::SQL($sql);
    }

}