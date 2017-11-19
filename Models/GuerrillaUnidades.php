<?php
Namespace Models;

class GuerrillaUnidades {

    private $guerrillaUnidadesId;
    private $cantidadUnidades;
    private $tipoUnidad;
    private $usuarioId;

    public function __construct() {
        $this->con = new Conexion();
        //capturar el tiempo
    }//ctor

    public function set($atributo, $contenido) {
        $this->$atributo = $contenido;
    }//set

    public function get($atributo) {
        return $this->$atributo;
    }//get
    
    public function create(){
         $this->con->conectar();
        $sql = "INSERT INTO guerrilla_unidades (cantidad_unidades, tipo_unidad, usuario_id)
                VALUES ('{$this->cantidadUnidades}', '{$this->tipoUnidad}', '{$this->usuarioId}'";
        //print $sql;
        $this->con->consultaSimple($sql);
    }//create
    
    public function update(){
        $sql = "UPDATE guerrilla_unidades set guerrilla_unidades_id = '{$this->guerrillaUnidadesId}' , cantidad_unidades = '{$this->cantidadUnidades}', 
            tipo_unidad = '{$this->tipoUnidad}' ,usuario_id = '{$this->usuarioId}'";
        $this->con->consultaSimple($sql);
    }//create
    
    public function delete(){
        $sql = "DELETE FROM guerrilla_unidades where guerrilla_unidades_id = '$this->guerrillaUnidadesId'";
        $this->con->consultaSimple($sql);
    }//create
    
    public function getGuerrillaUnidadesUsuario(){
         $sql = "SELECT * FROM guerrilla_unidades where usuario_id = '{$this->usuarioId}'";
        $datos = $this->con->consultaRetorno($sql);    
        return $datos; 
    }//create
    
}//class

?>