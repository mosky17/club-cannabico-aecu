<?php

require_once(dirname(__FILE__) . '/auth.php');

Auth::connect();

class Dato {

    public $codigo;
    public $valor;

    function __construct($codigo, $valor) {

        $this->codigo = $codigo;
        $this->valor = $valor;
    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $instance = new Dato($row['codigo'], $row['valor']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_dato($codigo)
    {
        $q = mysql_query("SELECT * FROM datos WHERE codigo='".$codigo."'");
        $result = Dato::mysql_to_instances($q);
        if (count($result) == 1) {
            return $result[0];
        } else {
            return array("error" => "Dato no encontrado");
        }
    }

    static public function get_datos()
    {
        $q = mysql_query("SELECT * FROM datos");
        $datos = Dato::mysql_to_instances($q);
        $indexado = array();
        for($i=0;$i<count($datos);$i++){
            $indexado[$datos[$i]->codigo] = $datos[$i]->valor;
        }
        return $indexado;
    }

    static public function actualizar_dato($codigo,$valor)
    {
        $dato = Dato::get_dato($codigo);
        if(array_key_exists("error",$dato)){
            //dato does not exists
            $q = mysql_query("INSERT INTO datos (codigo, valor) VALUES ('" . htmlspecialchars(mysql_real_escape_string($codigo)) . "', '" . htmlspecialchars(mysql_real_escape_string($valor)) . "')");
            if (mysql_affected_rows() == 1) {
                return array("ok" => true);
            } else {
                return array("error" => "Dato no modificado");
            }
        }else{
            $q = mysql_query("UPDATE datos SET valor='" . htmlspecialchars(mysql_real_escape_string($valor)) . "' WHERE codigo='" . htmlspecialchars(mysql_real_escape_string($codigo)) . "'");
            if (mysql_affected_rows() == 1) {
                return array("ok" => true);
            } else {
                return array("error" => "Dato no modificado");
            }
        }

    }

    static public function verificar_movimiento_caja($fecha){
        $caja_cerrada = Dato::get_dato("cajacerrada");
        $caja_cerrada = $caja_cerrada->valor;

        if(strtotime($fecha)<=strtotime($caja_cerrada)){
            return false;
        }else{
            return true;
        }
    }



} 