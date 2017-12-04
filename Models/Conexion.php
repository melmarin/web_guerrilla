<?php namespace Models;
	
class Conexion{
   /*
    private $datos = array(
	"host" => "163.178.107.130",
	"user" => "adm",
	"pass" => "saucr.092",
	"db" => "guerrilla_db"
    );*/
     
    private $datos = array(
	"host" => "localhost",
	"user" => "root",
	"pass" => "",
	"db" => "guerrilla_db"
    );
    
    private $con;

    public function __construct(){
        try {
            $this->con = new \PDO('mysql:host=localhost;dbname=guerrilla_db', $this->datos['user'], $this->datos['pass']);
            //echo 'Conectado a '.$this->con->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
        } catch(PDOException $ex) {
            echo 'Error conectando a la BBDD. '.$ex->getMessage(); 
          }
    }//ctor
    
    public function conectar(){
	try {
            $this->con = new \PDO('mysql:host=localhost;dbname=guerrilla_db', $this->datos['user'], $this->datos['pass']);
            //echo 'Conectado a '.$this->con->getAttribute(\PDO::ATTR_CONNECTION_STATUS);
        } catch(PDOException $ex) {
            echo 'Error conectando a la BBDD. '.$ex->getMessage(); 
          }
    }//conectar
 
    public function consultaSimple($sql){
        $this->conectar();
	$this->con->query($sql);
        $this->desconectar();
    }//consultaSimple 

    public function consultaRetorno($sql){
        $this->conectar();
    	$datos = $this->con->query($sql);
	$this->desconectar();
        return $datos;
    }//consultaRetorno
    
      public function desconectar(){
        return $this->con = NULL;       
    }//desconectar
    
}//class
?>

