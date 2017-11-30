<?php
Namespace Models;
require_once 'Conexion.php';

class Guerrilla {

    private $sql;
    private $cadena;
    private $datos;
    private $usuarioId;
    private $con;
    private $guerrillaAPI;

    public function __construct() {
        $this->con = new Conexion();
        //$this->con->desconectar();
        $this->guerrillaAPI = new \API\GuerrillaAPI();
        //$this->con->conectar();
        //capturar el tiempo
    }//ctor

    public function set($atributo, $contenido) {
        $this->$atributo = $contenido;
    }//set

    public function get($atributo) {
        return $this->$atributo;
    }//get
    
   
    public function create_guerrilla(){
        if($_GET['action']=='create_guerrilla'){
            $this->cadena = json_decode(file_get_contents('php://input', true));
            $objArr = (array)$this->cadena;
            print_r($objArr);
            if (empty($objArr)){
                 $this->guerrillaAPI->response(422,"error","Nothing to add. Check json");  
            }//if
            else {
                $tiempo = date("Y/m/d H:i:s");
                $username = $objArr['username'];
                $email = $objArr['email'];
                $tipo_guerrilla = $objArr['faction'];
                
                //$this->con->conectar();
                $this->sql = "call sp_create_guerrilla('$username','$email','$tiempo', '$tipo_guerrilla')";
                $this->datos = $this->con->consultaRetorno($this->sql);    
                $row = mysqli_fetch_assoc($this->datos);
                $array[] = $row;
                echo json_encode($array, JSON_PRETTY_PRINT); 
            }//else
        }//if
        $this->con->desconectar();
    }//create_guerrilla
    
    public function buy_guerrilla(){
        if($_GET['action']=='buy_guerrilla'){
            $this->cadena = json_decode(file_get_contents('php://input', true));
            $objArr = (array)$this->cadena;
            
            //$objeto = new \stdClass();
            /*foreach ($objArr as $value) {
                if(is_object($value)){
                    $value = 0;
                }
            }//for*/

            if (empty($objArr)){
                 $this->response(422,"error","Nothing to add. Check json");  
            }//if
            else{
                //print_r($objArr);
                $username = $objArr['username'];
                $this->getUsuarioId($username);
                $this->usuarioId;
                $assault = $objArr['assault'];
                $engineers = $objArr['engineers'];
                $tanks = $objArr['tanks'];
                $bunkers = $objArr ['bunkers'];
                
                //$this->con->desconectar();
                //$this->con.mysqli_init();
                //$this->con->conectar();
                $this->sql = "call sp_buy_guerrilla('$this->usuarioId', '$assault', '$engineers', '$tanks', '$bunkers')";
                $this->con->consultaSimple($this->sql);
            }//else
        }//if
    }//buy_guerrilla
    
    public function list_guerrillas(){
        $this->con->conectar();
        if($_GET['action']=='list_guerrillas'){
            $this->actualiza_puntaje();
            $this->actualiza_ranking();
            //$this->con->conectar();
            $this->sql = "call sp_obtener_ranking()";
            $this->datos = $this->con->consultaRetorno($this->sql);
            while($row = mysqli_fetch_assoc($this->datos)) {
                    $array[] = $row;
            }//while
            echo json_encode($array, JSON_PRETTY_PRINT); 
        }//if
    }//list_guerrillas
    
    public function actualiza_puntaje(){
        //$this->con->conectar();
        $this->sql = "call sp_actualiza_puntaje()";
        $this->con->consultaSimple($this->sql);
    }
    
    public function actualiza_ranking(){
        //$this->con->conectar();
        $this->sql = "call sp_actualiza_raking()";
        $this->con->consultaSimple($this->sql);
    }


    
     public function inspect_guerrilla_username($objJson){
         $this->cadena = json_decode($objJson);
         
     }//inspect_guerrilla_username
     
     public function inspect_guerrilla_id($objJson){
         $this->cadena = json_decode($objJson);
         $this->sql = "SELECT tipo_guerrilla, username, rank, puntaje, tiempo, email FROM usuario_guerrilla"
                 . "WHERE usuario_id = $this->cadena->id";
         $this->datos = $this->con->consultaRetorno($this->sql);
         //
         $this->sql = "SELECT tipo_recursos, cantidad_recursos FROM guerrilla_recursos"
                 . "WHERE usuario_id = $this->cadena->id";
         $this->datos += $this->con->consultaRetorno($this->sql);
         //
         $this->sql = "SELECT tipo_unidad, cantidad_unidades FROM guerrilla_unidades"
                 . "WHERE usuario_id = $this->cadena->id";
         $this->datos += $this->con->consultaRetorno($this->sql);
         return json_encode($this->datos); 
     }//inspect_guerrilla_id
    
    
    public function getUsuarioId ($username){
         //$this->con->conectar();
         $this->sql = "SELECT usuario_id FROM usuario_guerrilla where username = '$username'";
         $row = mysqli_fetch_array($this->con->consultaRetorno($this->sql)); 
         $this->usuarioId = $row['usuario_id'];
    }//getUsuarioId
    
}//class

?>
