<?php

require_once(dirname(__FILE__) . '/auth.php');

Auth::connect();

class Entrega {

    public $fecha;
    public $id;
    public $id_socio;
    public $gramos;
    public $id_genetica;
    public $notas;
    public $cancelado;

    function __construct($id, $fecha, $id_socio, $gramos, $id_genetica, $notas, $cancelado) {

        $this->id = $id;
        $this->fecha = $fecha;
        $this->id_socio = $id_socio;
        $this->gramos = $gramos;
        $this->id_genetica = $id_genetica;
        $this->notas = $notas;
        $this->cancelado = $cancelado;

    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $instance = new Entrega($row['id'], $row['fecha'], $row['id_socio'], $row['gramos'], $row['id_genetica'], $row['notas'], $row['cancelado']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_entrega($id)
    {
        $q = mysql_query("SELECT * FROM entregas WHERE id=".$id);
        $result = Entrega::mysql_to_instances($q);
        if (count($result) == 1) {
            return $result[0];
        } else {
            return array("error" => "Entrega no encontrada");
        }
    }

    static public function cancelar_entrega($id)
    {
        $q = mysql_query("UPDATE entregas SET cancelado=1 WHERE id=".$id);
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Entrega no cancelada");
        }
    }

    static public function get_lista_entregas()
    {
        $q = mysql_query("SELECT * FROM entregas WHERE cancelado=0 ORDER BY fecha DESC;");
        return Entrega::mysql_to_instances($q);
    }

    static public function get_entregas_socio($id)
    {
        $q = mysql_query("SELECT * FROM entregas WHERE id_socio=". $id . " AND cancelado=0 ORDER BY fecha DESC;");
        return Entrega::mysql_to_instances($q);
    }

    static public function ingresar_entrega($fecha, $id_socio, $gramos, $id_genetica, $notas){

        $q = mysql_query("INSERT INTO entregas (fecha, id_socio, gramos, id_genetica, notas) VALUES ('" . htmlspecialchars(mysql_real_escape_string($fecha)) . "', " .
        htmlspecialchars(mysql_real_escape_string($id_socio)) . ", '" . htmlspecialchars(mysql_real_escape_string($gramos)) . "', " .
        $id_genetica . ", '" . htmlspecialchars(mysql_real_escape_string($notas)) . "');");

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al ingresar entrega");
        }
    }

}