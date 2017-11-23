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
            $this->guerrilla->create_guerrilla();
            $this->guerrilla->buy_guerrilla();
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
    
    /**
 * Respuesta al cliente
 * @param int $code Codigo de respuesta HTTP
 * @param String $status indica el estado de la respuesta puede ser "success" o "error"
 * @param String $message Descripcion de lo ocurrido
 */
 function response($code=200, $status="", $message="") {
    http_response_code($code);
    if( !empty($status) && !empty($message) ){
        $response = array("status" => $status ,"message"=>$message);  
        echo json_encode($response,JSON_PRETTY_PRINT);    
    }            
 }//response   
    
}//end class
?>