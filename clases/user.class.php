<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";


class User extends conexion {
    private $table = "users";
    private $name = "";
    private $last_name = "";
    private $role = "";
    private $user = "";
    private $password = "";
    private $active = "";

    public function listUsers($page = 1){
        $inicio  = 0 ;
        $cantidad = 100;
        if($page > 1){
            $inicio = ($cantidad * ($page - 1)) +1 ;
            $cantidad = $cantidad * $page;
        }
        $query = "SELECT * FROM " . $this->table . " limit $inicio,$cantidad";
        $datos = parent::obtenerDatos($query);
        return ($datos);
    }

    public function getUser($id){
        $query = "SELECT * FROM " . $this->table . " WHERE user_id = '$id'";
        return parent::obtenerDatos($query);

    }

    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['name'])){
                    return $_respuestas->error_400();
                }else{
                    if ($arrayToken[0]['role'] !== 'admin') {
                        return $_respuestas->error_401();
                    } else {
                        $this->name = $datos['name'];
                        $this->last_name = $datos['last_name'];
                        $this->user = $datos['user'];
                        if(isset($datos['password'])) { $this->password = parent::encriptar($datos['password']); }
                        if(isset($datos['role'])) { $this->role = $datos['role']; }
                        if(isset($datos['name'])) { $this->name = $datos['name']; }
                        if(isset($datos['active'])) { $this->active = $datos['active']; }
                        $resp = $this->insertUser();
                        if($resp){
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "user_id" => $resp
                            );
                            return $respuesta;
                        }else{
                            return $_respuestas->error_500();
                        }
                    }
                }
            }
        }

    }

    public function delete($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['user_id'])){
                    return $_respuestas->error_400();
                }else{
                    if ($arrayToken[0]['role'] !== 'admin') {
                        return $_respuestas->error_401();
                    } else {
                        $this->user_id = $datos['user_id'];
                        $resp = $this->deleteUser();
                        if($resp){
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "user_id" => $this->user_id
                            );
                            return $respuesta;
                        } else{
                            return $_respuestas->error_500();
                        }
                    }
                }


            } else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }



     
    }

    public function put($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){
                if(!isset($datos['user_id'])){
                    return $_respuestas->error_400();
                }else{
                    if ($arrayToken[0]['role'] !== 'admin') {
                        return $_respuestas->error_401();
                    } else {
                        $this->name = $datos['name'];
                        $this->last_name = $datos['last_name'];
                        $this->user = $datos['user'];
                        if(isset($datos['password'])) { $this->password = parent::encriptar($datos['password']); }
                        if(isset($datos['role'])) { $this->role = $datos['role']; }
                        if(isset($datos['name'])) { $this->name = $datos['name']; }
                        if(isset($datos['active'])) { $this->active = $datos['active']; }
                        if(isset($datos['state'])) { $this->state = $datos['state']; }
                        if(isset($datos['user_id'])) { $this->user_id = $datos['user_id']; }
                        $resp = $this->editUser();
                        if($resp){
                            $respuesta = $_respuestas->response;
                            $respuesta["result"] = array(
                                "user_id" => $resp
                            );
                            return $respuesta;
                        }else{
                            return $_respuestas->error_500();
                        }
                    }
                }
            }
        }
    }


    private function insertUser(){
        $query = "INSERT INTO " . $this->table . " (name, last_name, user, password, role, active, state)
        values
        ('" . $this->name . "','" . $this->last_name ."', '" . $this->user . "', '" . $this->password . "', '" . $this->role . "', '" . $this->active . "', 1)"; 
        $resp = parent::nonQueryId($query);
        if($resp){
             return $resp;
        }else{
            return 0;
        }
    }

    private function editUser(){
        $query = "UPDATE " . $this->table . " SET name ='" . $this->name . "', last_name = '" . $this->last_name . "', password = '" . $this->password . "', role = '" .
        $this->role . "', user = '" . $this->user . "', active = '" . $this->active . "', state = '" . $this->state . "' WHERE user_id = '" . $this->user_id . "'"; 
        $resp = parent::nonQuery($query);
        if($resp >= 1){
             return $resp;
        }else{
            return 0;
        }
    }

    private function deleteUser(){
        $query = "UPDATE " . $this->table . " SET state ='" . "0" . "' WHERE user_id = '" . $this->user_id . "'"; 
        $resp = parent::nonQuery($query);
        if($resp >= 1 ){
            return $resp;
        }else{
            return 0;
        }
    }

    private function buscarToken(){
        $query = "SELECT  * FROM user_token WHERE token = '" . $this->token . "' AND active = 1";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }


}


?>