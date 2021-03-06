<?php

Namespace Models;

require_once 'Conexion.php';
require_once 'API/GuerrillaAPI.php';

class Guerrilla {

    private $sql;
    private $cadena;
    private $datos;
    private $usuarioId;
    private $con;
    private $guerrillaAPI;
    private $func;

    public function __construct() {
        $this->con = new Conexion();
    }

//ctor

    public function set($atributo, $contenido) {
        $this->$atributo = $contenido;
    }

//set

    public function get($atributo) {
        return $this->$atributo;
    }

//get 

    public function create_guerrilla() {
        if ($_GET['action'] == 'create_guerrilla') {
            $this->cadena = json_decode(file_get_contents('php://input', true));
            $objArr = (array) $this->cadena;
            if (empty($objArr)) {
                \API\GuerrillaAPI::response(422, "error", "Nothing to add. Check json");
            }//if
            else {
                $tiempo = date("Y/m/d H:i:s");
                $username = $objArr['username'];
                $email = $objArr['email'];
                $tipo_guerrilla = $objArr['faction'];
                $this->sql = "call sp_create_guerrilla('$username','$email','$tiempo','$tipo_guerrilla')";
                $this->datos = $this->con->consultaRetorno($this->sql);
                $row = $this->datos->fetch(\PDO::FETCH_ASSOC);
                $array[] = $row;
                echo $result = json_encode($array, JSON_PRETTY_PRINT);
                return $result;
            }//else
        }//if
    }

//create_guerrilla

    public function buy_guerrilla() {
        if ($_GET['action'] == 'buy_guerrilla') {
            $this->cadena = json_decode(file_get_contents('php://input', true));
            $objArr = (array) $this->cadena;
            $objArr = $this->arrayCastRecursive($objArr);
            print_r($objArr);

            if (empty($objArr)) {
                \API\GuerrillaAPI::response(422, "error", "Nothing to add. Check json");
            }//if
            else {
                $username = $objArr['username'];
                $this->getUsuarioId($username);
                $this->usuarioId;
                $assault = $objArr['offense']['assault'];
                $engineers = $objArr['offense']['engineers'];
                $tanks = $objArr['offense']['tanks'];
                $bunkers = $objArr ['defense']['bunkers'];
                $this->sql = "call sp_buy_guerrilla('$this->usuarioId','$assault','$engineers','$tanks','$bunkers')";
                $this->con->consultaSimple($this->sql);
                //echo $this->sql;
            }//else
        }//if
    }

//buy_guerrilla

    public function arrayCastRecursive($array) {
        if (is_array($array)) {
            foreach ($array as $key => $value) {
                if (is_array($value)) {
                    $array[$key] = $this->arrayCastRecursive($value);
                }
                if ($value instanceof \stdClass) {
                    $array[$key] = $this->arrayCastRecursive((array) $value);
                }
            }
        }//if
        if ($array instanceof \stdClass) {
            return $this->arrayCastRecursive((array) $array);
        }//if
        return $array;
    }

//arrayCastRecursive

    public function list_guerrillas() {
        if ($_GET['action'] == 'list_guerrillas') {
            $this->actualiza_puntaje();
            $this->actualiza_ranking();
            $sql = "call sp_obtener_ranking()";
            $this->datos = $this->con->consultaRetorno($sql);
            while ($row = $this->datos->fetch(\PDO::FETCH_ASSOC)) {
                $array[] = $row;
            }//while
            echo $result = json_encode($array, JSON_PRETTY_PRINT);
            return $result;
        }//if
    }

//list_guerrillas

    public function actualiza_puntaje() {
        $this->sql = "call sp_actualiza_puntaje()";
        $this->con->consultaSimple($this->sql);
    }

//actualiza_puntaje

    public function actualiza_ranking() {
        $this->sql = "call sp_actualiza_ranking()";
        $this->con->consultaSimple($this->sql);
    }

//actualiza_rankig

    public function inspect_guerrilla_username($objArr) {
        if (empty($objArr)) {
            \API\GuerrillaAPI::response(422, "error", "Nothing to add. Check json");
        }//if
        else {
            $this->getUsuarioId($objArr['username']);
            $this->sql = "SELECT tipo_guerrilla, username, rank, puntaje, tiempo, email FROM usuario_guerrilla"
                    . " WHERE usuario_id = '$this->usuarioId'";
            $this->datos = $this->con->consultaRetorno($this->sql);
            while ($row = $this->datos->fetch(\PDO::FETCH_ASSOC)) {
                $arrayUser[] = $row;
            }//while


            $this->sql = "SELECT tipo_recursos, cantidad_recursos FROM guerrilla_recursos"
                    . " WHERE usuario_id = '$this->usuarioId'";
            $this->datos = $this->con->consultaRetorno($this->sql);
            while ($row = $this->datos->fetch(\PDO::FETCH_ASSOC)) {
                $arrayRecursos[] = $row;
            }//while

            $this->sql = "SELECT tipo_unidad, cantidad_unidades FROM guerrilla_unidades"
                    . " WHERE usuario_id = '$this->usuarioId'";
            $this->datos = $this->con->consultaRetorno($this->sql);
            while ($row = $this->datos->fetch(\PDO::FETCH_ASSOC)) {
                $arrayUnidades[] = $row;
            }//while
        }

        $arrayFinal = array("id" => $this->usuarioId, "faction" => $arrayUser[0]['tipo_guerrilla'], "username" => $arrayUser[0]['username'],
            "ranking" => (int) $arrayUser[0]['rank'], "points" => (int) $arrayUser[0]['puntaje'], "timestamp" => date($arrayUser[0]['tiempo']),
            "email" => $arrayUser[0]['email'], "resources" => Array("oil" => (int) $arrayRecursos[2]['cantidad_recursos'],
                "money" => (int) $arrayRecursos[0]['cantidad_recursos'], "people" => (int) $arrayRecursos[1]['cantidad_recursos']),
            "defense" => Array("bunkers" => (int) $arrayUnidades[3]['cantidad_unidades']), "offense" => Array("assault" => (int) $arrayUnidades[0]['cantidad_unidades'],
                "engineers" => (int) $arrayUnidades[1]['cantidad_unidades'], "tanks" => (int) $arrayUnidades[2]['cantidad_unidades']));
        echo $result = json_encode($arrayFinal, JSON_PRETTY_PRINT);
        return $result;
        //echo 'PRUEBA EXITOSA USERNAME!!!!!!!!!!!!';
    }

//inspect_guerrilla_username

    public function inspect_guerrilla_id($objArr) {
        if (empty($objArr)) {
            \API\GuerrillaAPI::response(422, "error", "Nothing to add. Check json");
        }//if
        else {
            $this->usuarioId = $objArr['id'];
            $this->sql = "SELECT tipo_guerrilla, username, rank, puntaje, tiempo, email FROM usuario_guerrilla"
                    . " WHERE usuario_id = '$this->usuarioId'";
            $this->datos = $this->con->consultaRetorno($this->sql);
            while ($row = $this->datos->fetch(\PDO::FETCH_ASSOC)) {
                $arrayUser[] = $row;
            }//while


            $this->sql = "SELECT tipo_recursos, cantidad_recursos FROM guerrilla_recursos"
                    . " WHERE usuario_id = '$this->usuarioId'";
            $this->datos = $this->con->consultaRetorno($this->sql);
            while ($row = $this->datos->fetch(\PDO::FETCH_ASSOC)) {
                $arrayRecursos[] = $row;
            }//while

            $this->sql = "SELECT tipo_unidad, cantidad_unidades FROM guerrilla_unidades"
                    . " WHERE usuario_id = '$this->usuarioId'";
            $this->datos = $this->con->consultaRetorno($this->sql);
            while ($row = $this->datos->fetch(\PDO::FETCH_ASSOC)) {
                $arrayUnidades[] = $row;
            }//while
        }

        $arrayFinal = array("id" => $this->usuarioId, "faction" => $arrayUser[0]['tipo_guerrilla'], "username" => $arrayUser[0]['username'],
            "ranking" => (int) $arrayUser[0]['rank'], "points" => (int) $arrayUser[0]['puntaje'], "timestamp" => date($arrayUser[0]['tiempo']),
            "email" => $arrayUser[0]['email'], "resources" => Array("oil" => (int) $arrayRecursos[2]['cantidad_recursos'],
                "money" => (int) $arrayRecursos[0]['cantidad_recursos'], "people" => (int) $arrayRecursos[1]['cantidad_recursos']),
            "defense" => Array("bunkers" => (int) $arrayUnidades[3]['cantidad_unidades']), "offense" => Array("assault" => (int) $arrayUnidades[0]['cantidad_unidades'],
                "engineers" => (int) $arrayUnidades[1]['cantidad_unidades'], "tanks" => (int) $arrayUnidades[2]['cantidad_unidades']));
        echo $result = json_encode($arrayFinal, JSON_PRETTY_PRINT);
        return $result;

        //echo 'PRUEBA EXITOSA ID!!!!!!!!!!!!';
    }

//inspect_guerrilla_id

    public function getUsuarioId($username) {
        $this->sql = "SELECT usuario_id FROM usuario_guerrilla where username = '$username'";
        $this->datos = $this->con->consultaRetorno($this->sql);
        $row = $this->datos->fetch(\PDO::FETCH_ASSOC);
        $this->usuarioId = $row['usuario_id'];
    }

//getUsuarioId

    public function attackGuerrilla($user_id_df, $user_id_of) {

        if ($_GET['action'] == 'attack_guerrilla') {
            $this->cadena = json_decode(file_get_contents('php://input', true));
            $objArr = (array) $this->cadena;
            $this->sql1 = "call sp_obtener_indices('$user_id_df')";
            $this->user_indices_df = $this->con->consultaRetorno($this->sql1);
             while ($row = $this->user_indices_df->fetch(\PDO::FETCH_ASSOC)) {
                $defensorIndices[] = $row;
            }//while
            $this->sql2 = "call sp_obtener_indices('$user_id_of')";
            $this->user_indices_of = $this->con->consultaRetorno($this->sql2);
             while ($row = $this->user_indices_of->fetch(\PDO::FETCH_ASSOC)) {
                $atacanteIndices[] = $row;
            }//while
            
            $this->sql3 = "SELECT tipo_unidad, cantidad_unidades FROM guerrilla_unidades"
                    . " WHERE usuario_id = '$this->$user_id_df'";
            $this->datosDefensor = $this->con->consultaRetorno($this->sql3);
            while ($row = $this->datosDefensor->fetch(\PDO::FETCH_ASSOC)) {
                $unidadesDefensor[] = $row;
            }//while
            
            $this->sql4 = "SELECT tipo_unidad, cantidad_unidades FROM guerrilla_unidades"
                    . " WHERE usuario_id = '$this->$user_id_of'";
            $this->datosAtacante = $this->con->consultaRetorno($this->sql4);
            while ($row = $this->datosAtacante->fetch(\PDO::FETCH_ASSOC)) {
                $unidadesAtacante[] = $row;
            }//while
            $DI = ($defensorIndices[1]/(($atacanteIndices[0]+$defensorIndices[1])+0.1));
            $AI = ($atacanteIndices[0]/(($defensorIndices[1]+$atacanteIndices[0])+0.1));
            
            $danno_asalto_defensor = ($unidadesDefensor[0]*0.5)+($unidadesDefensor[1]*0.3)
                    +($unidadesDefensor[2]*5)+($unidadesDefensor[3]*5);
            
            $danno_ingeniero_defensor = ($unidadesDefensor[0]*0.8)+($unidadesDefensor[1]*0.5)
                    +($unidadesDefensor[2]*5)+($unidadesDefensor[3]*5);
            //0.8	0.5	5	5
            $danno_tanque_defensor = ($unidadesDefensor[0]*0.1)+($unidadesDefensor[1]*0.9)
                    +($unidadesDefensor[2]*0.6)+($unidadesDefensor[3]*0.9);
            //0.1	0.9	0.6	0.9
            $danno_bunker_defensor = ($unidadesDefensor[0]*0.05)+($unidadesDefensor[1]*0.9)
                    +($unidadesDefensor[2]*1)+($unidadesDefensor[3]*0);
          
            $und_asalto_perdidas_atacante = floor($danno_asalto_defensor*$DI);
            $und_ingeniero_perdidas_atacante = floor($danno_ingeniero_defensor*$DI);
            $und_tanque_perdidas_atacante = floor($danno_tanque_defensor*$DI);
            $und_bunker_perdidas_atacante = floor($danno_bunker_defensor*$DI);
            
            $danno_asalto_atacante = ($unidadesAtacante[0]*0.5)+($unidadesAtacante[1]*0.3)
                    +($unidadesAtacante[2]*5)+($unidadesAtacante[3]*5);
            
            $danno_ingeniero_atacante = ($unidadesAtacante[0]*0.8)+($unidadesAtacante[1]*0.5)
                    +($unidadesAtacante[2]*5)+($unidadesAtacante[3]*5);
            //0.8	0.5	5	5
            $danno_tanque_atacante = ($unidadesAtacante[0]*0.1)+($unidadesAtacante[1]*0.9)
                    +($unidadesAtacante[2]*0.6)+($unidadesAtacante[3]*0.9);
            //0.1	0.9	0.6	0.9
            $danno_bunker_atacante = ($unidadesAtacante[0]*0.05)+($unidadesAtacante[1]*0.9)
                    +($unidadesAtacante[2]*1)+($unidadesAtacante[3]*0);
            
            $und_asalto_perdidas_defensor = floor($danno_asalto_atacante*$DI);
            $und_ingeniero_perdidas_defensor = floor($danno_ingeniero_atacante*$DI);
            $und_tanque_perdidas_defensor = floor($danno_tanque_atacante*$DI);
            $und_bunker_perdidas_defensor = floor($danno_bunker_atacante*$DI);
            
            if ($AI>$DI) {
                $oil = rand ( 0 , intval($atacanteIndices[2]));
                $money = intval($atacanteIndices[2])-$oil;
            }
        }//getUsuarioId
    }

}

//class
?>
