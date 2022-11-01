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
    private $documents_id = "";
    private $id_user = "";
    private $id_customer = "";
    private $token = "";

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

        if(!isset($datos['token'])){
            return $_respuestas->error_401();
        }else{
            $this->token = $datos['token'];
            $arrayToken =   $this->buscarToken();
            if($arrayToken){
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
                    if(isset($datos['payment_type'])) { $this->payment_type = $datos['payment_type']; }
                    if(isset($datos['clauses'])) { $this->clauses = $datos['clauses']; }
                    if(isset($datos['documents_id'])) { $this->documents_id = $datos['documents_id']; }
                    if(isset($datos['id_user'])) { $this->id_user = $datos['id_user']; }
                    if(isset($datos['id_customer'])) { $this->id_customer = $datos['id_customer']; }
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
        }

    }

    private function insertLoan(){
        $query = "INSERT INTO " . $this->table . " (loan_type, amount, interest, fee, fee_amount, payment_type, coin, documents_id, clauses)
        values
        ('" . $this->loan_type . "','" . $this->amount ."', '" . $this->interest . "', '" . $this->fee . "', '" . $this->fee_amount . "', '" . $this->payment_type . "', '" . $this->coin . "', '" . $this->documents_id . "', '" . $this->clauses . "')"; 
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
                    if(!isset($datos['loan_id'])){
                        return $_respuestas->error_400();
                    }else{
                        $this->loan_id = $datos['loan_id'];
                        $this->loan_type = $datos['loan_type'];
                        $this->amount = $datos['amount'];
                        $this->interest = $datos['interest'];
                        if(isset($datos['fee'])) { $this->fee = $datos['fee']; }
                        if(isset($datos['fee_amount'])) { $this->fee_amount = $datos['fee_amount']; }
                        if(isset($datos['loan_type'])) { $this->loan_type = $datos['loan_type']; }
                        if(isset($datos['coin'])) { $this->coin = $datos['coin']; }
                        if(isset($datos['payment_type'])) { $this->payment_type = $datos['payment_type']; }
                        if(isset($datos['clauses'])) { $this->clauses = $datos['clauses']; }
                        if(isset($datos['documents_id'])) { $this->documents_id = $datos['documents_id']; }
                        // if(isset($datos['id_user'])) { $this->id_user = $datos['id_user']; }
                        // if(isset($datos['id_customer'])) { $this->id_customer = $datos['id_customer']; }
                        $resp = $this->editLoan();
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
            }
        }
    }
    

    private function editLoan(){
        $query = "UPDATE " . $this->table . " SET loan_type ='" . $this->loan_type . "', amount = '" . $this->amount . "', interest = '" . $this->interest . "', fee = '" .
        $this->fee . "', fee_amount = '" . $this->fee_amount . "', payment_type = '" . $this->payment_type . "', coin = '" . $this->coin . "', documents_id = '" . $this->documents_id .
        "', clauses = '" . $this->clauses . "' WHERE loan_id = '" . $this->loan_id . "'"; 
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
            if($arrayToken && $arrayToken[0]['role'] !== 'admin'){

                if(!isset($datos['loan_id'])){
                    return $_respuestas->error_400();
                }else{
                    $this->loan_id = $datos['loan_id'];
                    $resp = $this->deleteLoan();
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


    private function deleteLoan(){
        $query = "DELETE FROM " . $this->table . " WHERE loan_id= '" . $this->loan_id . "'";
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


    private function actualizarToken($tokenid){
        $date = date("Y-m-d H:i");
        $query = "UPDATE user_token SET date = '$date' WHERE token_id = '$tokenid' ";
        $resp = parent::nonQuery($query);
        if($resp >= 1){
            return $resp;
        }else{
            return 0;
        }
    }

}





?>