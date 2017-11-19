<?php

Namespace Models;

class Usuario {

    private $usuarioId;
    private $username;
    private $email;
    private $rank;
    private $puntaje;
    private $tiempo;
    private $tipo_guerrilla;

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
        $sql = "INSERT INTO usuario_guerrilla (username, email, rank, puntaje, tiempo, tipo_guerrilla)
                VALUES ('{$this->username}', '{$this->email}', '{$this->rank}','{$this->puntaje}',
                    '{$this->tiempo}','{$this->tipo_guerrilla}'";
        //print $sql;
        $this->con->consultaSimple($sql);
    }//create
    
    public function update(){
        $sql = "UPDATE usuario_guerrilla set usuario_id = '{$this->usuarioId}' , username = '{$this->username}', 
            email = '{$this->email}' , rank = '{$this->rank}', puntaje = '{$this->puntaje}', tiempo = '{$this->tiempo}', 
            tipo_guerrilla = '{$this->tipo_guerrilla}'";
        $this->con->consultaSimple($sql);
    }//create
    
    public function delete(){
        $sql = "DELETE FROM usuario_guerrilla where usuario_id = '$this->usuarioId'";
        $this->con->consultaSimple($sql);
    }//create
    
    public function getUsuarios(){
         $sql = "SELECT * FROM usuario_guerrilla";
        $datos = $this->con->consultaRetorno($sql);    
        return $datos; 
    }//create
    
}//class
?>

