<?php

Namespace Models;

class Recurso {

    private $recursoId;
    private $tipo;
    private $cantidad;
    private $guerrillaUnidadesId;

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
        $sql = "INSERT INTO recurso (tipo, cantidad, guerrilla_unidades_id)
                VALUES ('{$this->tipo}', '{$this->cantidad}', '{$this->guerrillaUnidadesId}'";
        //print $sql;
        $this->con->consultaSimple($sql);
    }//create
    
    public function update(){
        $sql = "UPDATE recurso set recurso_id = '{$this->recursoId}' , tipo = '{$this->tipo}', 
            cantidad = '{$this->cantidad}' ,guerrilla_unidades_id = '{$this->guerrillaUnidadesId}'";
        $this->con->consultaSimple($sql);
    }//create
    
    public function delete(){
        $sql = "DELETE FROM unidad_batalla where unidad_batalla_id = '$this->unidadBatallaId'";
        $this->con->consultaSimple($sql);
    }//create
    
    public function getRecursosGuerrilla(){
         $sql = "SELECT * FROM recurso where guerrilla_unidades_id = '{$this->guerrillaUnidadesId}'";
        $datos = $this->con->consultaRetorno($sql);    
        return $datos; 
    }//create
    
}//class
?>

