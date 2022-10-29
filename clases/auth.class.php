<?php
require_once 'conexion/conexion.php';
require_once 'respuestas.class.php';


class auth extends conexion{

    public function login($json){
      
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);
        if(!isset($datos['user']) || !isset($datos["password"])){
            //error con los campos
            return $_respuestas->error_400();
        }else{
            //todo esta bien 
            $user = $datos['user'];
            $password = $datos['password'];
            $password = parent::encriptar($password);
            $datos = $this->obtenerDatosuser($user);
            if($datos){
                //verificar si la contraseña es igual
                    if($password == $datos[0]['password']){
                                //crear el token
                                $verificar  = $this->insertarToken($datos[0]['user_id']);
                                if($verificar){
                                        // si se guardo
                                        $result = $_respuestas->response;
                                        $result["result"] = array(
                                            "token" => $verificar
                                        );
                                        return $result;
                                }else{
                                        //error al guardar
                                        return $_respuestas->error_500("Error interno, No hemos podido guardar");
                                }
                    }else{
                        //la contraseña no es igual
                        return $_respuestas->error_200("El password es invalido");
                    }
            }else{
                //no existe el user
                return $_respuestas->error_200("El usuaro $user  no existe ");
            }
        }
    }



    private function obtenerDatosuser($user){
        $query = "SELECT user_id, password,user FROM users WHERE user = '$user'";
        $datos = parent::obtenerDatos($query);
        if(isset($datos[0]["user_id"])){
            return $datos;
        }else{
            return 0;
        }
    }

    private function insertarToken($user_id){
        $val = true;
        //funciones de php para generar el token - la 2da genera una cadena aleatoria
        $token = bin2hex(openssl_random_pseudo_bytes(16,$val));
        $date = date("Y-m-d H:i");
        $active = true;
        $query = "INSERT INTO user_token (user_id, token, active, date)VALUES('$user_id','$token','$active','$date')";
        $verifica = parent::nonQuery($query);
        if($verifica){
            return $token;
        }else{
            return 0;
        }
    }


}




?>