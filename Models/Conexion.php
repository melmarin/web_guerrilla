<?php namespace Models;
	
class Conexion{
   
    private $datos = array(
	"host" => "163.178.107.130",
	"user" => "adm",
	"pass" => "saucr.092",
	"db" => "guerrilla_db"
    );
     
   /* private $datos = array(
	"host" => "localhost",
	"user" => "root",
	"pass" => "",
	"db" => "guerrilla_bd"
    );*/
    

    private $con;

    public function __construct(){
	// \ para que identifica la clase global mysqli cuando utilizamos namespace
	$this->con = new \mysqli($this->datos['host'],$this->datos['user'], $this->datos['pass'],$this->datos['db']);
        //Acentos
        \mysqli_set_charset($this->con, "utf8");
    }
    
    public function conectar(){
	// \ para que identifica la clase global mysqli cuando utilizamos namespace
	$this->con = new \mysqli($this->datos['host'],$this->datos['user'], $this->datos['pass'],$this->datos['db']);
        //Acentos
        \mysqli_set_charset($this->con, "utf8");
    }
    
    public function consultaSimple($sql){
	$this->con->query($sql);
    } 

    public function consultaRetorno($sql){
    	$datos = $this->con->query($sql);
	return $datos;
    }
}
?>

