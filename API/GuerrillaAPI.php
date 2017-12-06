<?php
Namespace API;
require_once 'Models/Guerrilla.php';

class GuerrillaAPI {    
    
    private $guerrilla;
    
     public function __construct() {
         $this->guerrilla = new \Models\Guerrilla();
    }//ctor
    
    public function API(){
        header('Content-Type: application/JSON');                
        $method = $_SERVER['REQUEST_METHOD'];
        switch ($method) {
        case 'GET'://consulta
            $this->guerrilla->list_guerrillas();
            break;     
        case 'POST'://inserta
            $actual_link = "http://$_SERVER[REQUEST_URI]";
            $actual_link = $this->getAction($actual_link);
            $actual_link;
            $cadena = json_decode(file_get_contents('php://input', true));
            $objArr = (array) $cadena;
            //print_r($objArr);
            $this->callMethod($actual_link, $objArr);
            break;                
        case 'PUT'://actualiza
            echo 'PUT';
            break;      
        case 'DELETE'://elimina
            echo 'DELETE';
            break;
        default://metodo NO soportado
            echo 'METODO NO SOPORTADO';
            break;
        }
    }//API
    
    public function getAction($link){
        $action="";
        $cont=0;
        for($i=0; $i<strlen($link); $i++) {
            if ($link[$i] == '/') {
                $cont++;
            }//if
            if ($cont == 4){
                $action = substr($link, $i+1, strlen($link));
                break;
            }//if
        }//forr
        return $action;
    }//getAction
    
    public function callMethod($actual_link,$objArr ){
         if($actual_link == 'create_guerrilla'){
                  $this->guerrilla->create_guerrilla();
            } 
         else if ($actual_link == 'buy_guerrilla'){
                $this->guerrilla->buy_guerrilla();
         }
         else if ($actual_link == 'inspect_guerrilla'){
            if(isset($objArr['id'])){
                $this->guerrilla->inspect_guerrilla_id($objArr);
            }//if
            else {
                $this->guerrilla->inspect_guerrilla_username($objArr);
            }
          }
          else if ($actual_link == 'attack_guerrilla'){
                //$this->guerrilla->buy_guerrilla();
          }
    }

    /**
 * Respuesta al cliente
 * @param int $code Codigo de respuesta HTTP
 * @param String $status indica el estado de la respuesta puede ser "success" o "error"
 * @param String $message Descripcion de lo ocurrido
 */
 public static function response($code=200, $status="", $message="") {
    http_response_code($code);
    if( !empty($status) && !empty($message) ){
        $response = array("status" => $status ,"message"=>$message);  
        echo json_encode($response,JSON_PRETTY_PRINT);    
    }            
 }//response   
    
}//end class
?>