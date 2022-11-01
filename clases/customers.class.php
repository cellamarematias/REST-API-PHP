<?php
require_once "conexion/conexion.php";
require_once "respuestas.class.php";


class customer extends conexion {
    private $table = "customer";

    public function customersList($page) {
        $init = 0;
        $quantity = 100;
        if($page > 1) {
            $init = ($quantity * ($page - 1)) +1;
            $quantity = $quantity * $page;
        }
        $query = "SELECT * customer FROM " . $this->table . " LIMIT $init, $quantity";
        print_r($query);

    }

}



?>