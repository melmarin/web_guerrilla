<?php

Namespace Models;

class UnidadBatalla {

    private $unidadBatallaId;
    private $tipo;
    private $cantidad;
    private $poderDefensa;
    private $indiceDefensa;
    private $poderAtaque;
    private $indiceAtaque;
    private $capacidadPillaje;
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
        $sql = "INSERT INTO unidad_batalla (tipo, cantidad, poder_defensa, indice_defensa, poder_ataque, indice_ataque, capacidad_pillaje, guerrilla_unidades_id)
                VALUES ('{$this->tipo}', '{$this->cantidad}', '{$this->poderDefensa}','{$this->indiceDefensa}',
                    '{$this->poderAtaque}','{$this->indiceAtaque}', '{$this->capacidadPillaje}', '{$this->guerrillaUnidadesId}'";
        //print $sql;
        $this->con->consultaSimple($sql);
    }//create
    
    public function update(){
        $sql = "UPDATE unidad_batalla set unidad_batalla_id = '{$this->unidadBatallaId}' , tipo = '{$this->tipo}', 
            cantidad = '{$this->cantidad}' , poder_defensa = '{$this->poderDefensa}', indice_defensa = '{$this->indiceDefensa}', "
            . "poder_ataque = '{$this->poderAtaque}', indice_ataque = '{$this->indiceAtaque}', capacidad_pillaje = '{$this->capacidadPillaje}',
                guerrilla_unidades_id = '{$this->guerrillaUnidadesId}'";
        $this->con->consultaSimple($sql);
    }//create
    
    public function delete(){
        $sql = "DELETE FROM unidad_batalla where unidad_batalla_id = '$this->unidadBatallaId'";
        $this->con->consultaSimple($sql);
    }//create
    
    public function getUnidadesGuerrilla(){
         $sql = "SELECT * FROM unidad_batalla where guerrilla_unidades_id = '{$this->guerrillaUnidadesId}'";
        $datos = $this->con->consultaRetorno($sql);    
        return $datos; 
    }//create
    
}//class
?>