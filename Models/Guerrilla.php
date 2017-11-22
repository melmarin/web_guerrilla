<?php
Namespace Models;

class GuerrillaUnidades {

    private $sql;
    private $cadena;
    private $datos;
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
    
    public function  create_guerrilla($objJson){
        $this->cadena = json_decode($objJson);
        $this->sql = "SELECT username, usuario_id FROM usuario_guerrilla where username =".$this->cadena->username;
        $this->datos = $this->con->consultaRetorno($this->sql);    
        return json_encode($this->datos); 
    }//create_guerrilla
    
    public function buy_guerrilla($objJson){
        $this->cadena = json_decode($objJson);
        $id =$this->getUsuarioId($this->cadena->username);
        $this->sql = "call sp_buy_guerilla('{$this->usuarioId}', {'$this->cadena->assault'}"
                . ", {'$this->cadena->engineers'}, , {'$this->cadena->tanks'}, {'$this->cadena->bunkers'})";
        $this->con->consultaSimple($this->sql);
    }//
    
    public function list_guerrillas(){
        $this->sql = "call sp_actualiza_puntaje()";
        $this->con->consultaSimple($this->$sql);
        $this->sql = "call sp_actualiza_raking()";
        $this->con->consultaSimple($this->$sql);
        $this->sql = "call sp_obtener_ranking()";
        $this->datos = $this->con->consultaRetorno($this->sql); 
        return json_encode($this->datos); 
    }
    
    public function getUsuarioId ($username){
         $this->sql = "SELECT usuario_id FROM usuario_guerrilla where username =".$username;
         $this->usuarioId = $this->con->consultaRetorno($this->sql); 
    }//
    
}//class

?>
