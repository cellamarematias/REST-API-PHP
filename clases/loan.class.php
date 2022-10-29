<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";


class loan extends conexion {

    private $table = "loans";
    private $loan_id = "";
    private $loan_type = "";
    private $amount = "";
    private $interest = "";
    private $fee = "";
    private $fee_amount = "";
    private $created_at = "";
    private $payment_type = "";
    private $coin = "";
    private $start_date = "";
    private $clauses = "";
    private $id_user = "";
    private $id_customer = "";

    public function listloan($page = 1){
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

    public function getLoan($id){
        $query = "SELECT * FROM " . $this->table . " WHERE loan_id = '$id'";
        return parent::obtenerDatos($query);

    }

    public function post($json){
        $_respuestas = new respuestas;
        $datos = json_decode($json,true);

        //loan_type, amount, interest, rate, fee, fee_amount, payment_type, coin, start_date, clauses, id_user, id_customer
                if(!isset($datos['loan_type']) || !isset($datos['amount']) || !isset($datos['interest'])){
                    return $_respuestas->error_400();
                }else{
                    $this->loan_type = $datos['loan_type'];
                    $this->amount = $datos['amount'];
                    $this->interest = $datos['interest'];
                    if(isset($datos['fee'])) { $this->fee = $datos['fee']; }
                    if(isset($datos['fee_amount'])) { $this->fee_amount = $datos['fee_amount']; }
                    if(isset($datos['loan_type'])) { $this->loan_type = $datos['loan_type']; }
                    if(isset($datos['coin'])) { $this->coin = $datos['coin']; }
                    if(isset($datos['clauses'])) { $this->clauses = $datos['clauses']; }
                    if(isset($datos['id_user'])) { $this->id_user = $datos['id_user']; }
                    if(isset($datos['id_customer'])) { $this->id_customer = $datos['id_customer']; }
                    if(isset($datos['payment_type'])) { $this->payment_type = $datos['payment_type']; }
                    $resp = $this->insertLoan();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "loan_id" => $resp
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

    }

    // public function post($json){
    //     $_respuestas = new respuestas;
    //     $datos = json_decode($json,true);

    //     if(!isset($datos['token'])){
    //             return $_respuestas->error_401();
    //     }else{
    //         $this->token = $datos['token'];
    //         $arrayToken =   $this->buscarToken();
    //         if($arrayToken){

    //             if(!isset($datos['nombre']) || !isset($datos['dni']) || !isset($datos['correo'])){
    //                 return $_respuestas->error_400();
    //             }else{
    //                 $this->nombre = $datos['nombre'];
    //                 $this->dni = $datos['dni'];
    //                 $this->correo = $datos['correo'];
    //                 if(isset($datos['telefono'])) { $this->telefono = $datos['telefono']; }
    //                 if(isset($datos['direccion'])) { $this->direccion = $datos['direccion']; }
    //                 if(isset($datos['codigoPostal'])) { $this->codigoPostal = $datos['codigoPostal']; }
    //                 if(isset($datos['genero'])) { $this->genero = $datos['genero']; }
    //                 if(isset($datos['fechaNacimiento'])) { $this->fechaNacimiento = $datos['fechaNacimiento']; }
    //                 $resp = $this->insertarPaciente();
    //                 if($resp){
    //                     $respuesta = $_respuestas->response;
    //                     $respuesta["result"] = array(
    //                         "loan_id" => $resp
    //                     );
    //                     return $respuesta;
    //                 }else{
    //                     return $_respuestas->error_500();
    //                 }
    //             }

    //         }else{
    //             return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
    //         }
    //     }
    // }


    private function insertLoan(){
        $query = "INSERT INTO " . $this->table . " (loan_type, amount, interest, fee, fee_amount)
        values
        ('" . $this->loan_type . "','" . $this->amount ."', '" . $this->interest . "', '" . $this->fee . "', '" . $this->fee_amount . "')"; 
        $resp = parent::nonQueryId($query);
        if($resp){
             return $resp;
        }else{
            return 0;
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
                if(!isset($datos['loan_id'])){
                    return $_respuestas->error_400();
                }else{
                    $this->loan_id = $datos['loan_id'];
                    if(isset($datos['nombre'])) { $this->nombre = $datos['nombre']; }
                    if(isset($datos['dni'])) { $this->dni = $datos['dni']; }
                    if(isset($datos['correo'])) { $this->correo = $datos['correo']; }
                    if(isset($datos['telefono'])) { $this->telefono = $datos['telefono']; }
                    if(isset($datos['direccion'])) { $this->direccion = $datos['direccion']; }
                    if(isset($datos['codigoPostal'])) { $this->codigoPostal = $datos['codigoPostal']; }
                    if(isset($datos['genero'])) { $this->genero = $datos['genero']; }
                    if(isset($datos['fechaNacimiento'])) { $this->fechaNacimiento = $datos['fechaNacimiento']; }
        
                    $resp = $this->modificarPaciente();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "loan_id" => $this->loan_id
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }


    }


    private function modificarPaciente(){
        $query = "UPDATE " . $this->table . " SET Nombre ='" . $this->nombre . "',Direccion = '" . $this->direccion . "', DNI = '" . $this->dni . "', CodigoPostal = '" .
        $this->codigoPostal . "', Telefono = '" . $this->telefono . "', Genero = '" . $this->genero . "', FechaNacimiento = '" . $this->fechaNacimiento . "', Correo = '" . $this->correo .
         "' WHERE loan_id = '" . $this->loan_id . "'"; 
        $resp = parent::nonQuery($query);
        if($resp >= 1){
             return $resp;
        }else{
            return 0;
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

                if(!isset($datos['loan_id'])){
                    return $_respuestas->error_400();
                }else{
                    $this->loan_id = $datos['loan_id'];
                    $resp = $this->eliminarPaciente();
                    if($resp){
                        $respuesta = $_respuestas->response;
                        $respuesta["result"] = array(
                            "loan_id" => $this->loan_id
                        );
                        return $respuesta;
                    }else{
                        return $_respuestas->error_500();
                    }
                }

            }else{
                return $_respuestas->error_401("El Token que envio es invalido o ha caducado");
            }
        }



     
    }


    private function eliminarPaciente(){
        $query = "DELETE FROM " . $this->table . " WHERE loan_id= '" . $this->loan_id . "'";
        $resp = parent::nonQuery($query);
        if($resp >= 1 ){
            return $resp;
        }else{
            return 0;
        }
    }


    private function buscarToken(){
        $query = "SELECT  TokenId,UsuarioId,Estado from usuarios_token WHERE Token = '" . $this->token . "' AND Estado = 'Activo'";
        $resp = parent::obtenerDatos($query);
        if($resp){
            return $resp;
        }else{
            return 0;
        }
    }


    private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE usuarios_token SET Fecha = '$date' WHERE TokenId = '$tokenid' ";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }



}





?>