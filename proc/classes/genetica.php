<?php

require_once(dirname(__FILE__) . '/auth.php');
require_once(dirname(__FILE__) . '/pago.php');

Auth::connect();

class Genetica {

    public $id;
    public $detalles;
    public $nombre;
    public $origen;

    function __construct($id, $detalles, $nombre, $origen)
    {

        $this->id = $id;
        $this->detalles = $detalles;
        $this->nombre = $nombre;
        $this->origen = $origen;
    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $instance = new Genetica($row['id'], $row['detalles'], $row['nombre'], $row['origen']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_lista_geneticas()
    {
        $q = mysql_query("SELECT * FROM geneticas ORDER BY nombre;");
        return Genetica::mysql_to_instances($q);
    }

    static public function get_producido_por_genetica($id){

        $q = mysql_query("SELECT sum(gramos) as count FROM entregas WHERE id_genetica=" . $id);
        $count=mysql_fetch_assoc($q);
        return $count['count'];
    }

    static public function ingresar_genetica($nombre,$origen,$detalles){

        $q = mysql_query("INSERT INTO geneticas (nombre, origen, detalles) VALUES ('" . htmlspecialchars(mysql_real_escape_string($nombre)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($origen)) . "', '" . htmlspecialchars(mysql_real_escape_string($detalles)) . "')");

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al insertar genetica");
        }

    }

    static public function update_genetica($id,$nombre,$origen,$detalles)
    {
        $q = mysql_query("UPDATE geneticas SET nombre='".htmlspecialchars(mysql_real_escape_string($nombre))."',".
            " origen='".htmlspecialchars(mysql_real_escape_string($origen))."',".
            " detalles='".htmlspecialchars(mysql_real_escape_string($detalles))."' WHERE id=".$id);
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Genetica no modificado");
        }
    }

    static public function delete_genetica($id)
    {
        $q = mysql_query("SELECT * FROM entregas WHERE id_genetica=" . $id . " AND cancelado=0");
        if(mysql_num_rows($q) > 0){
            return array("error" => "No se puede borrar una genetica que fue entregada");
        }else{
            $q = mysql_query("DELETE FROM geneticas WHERE id=".$id);
            if (mysql_affected_rows() == 1) {
                return true;
            } else {
                return array("error" => "Genetica no borrada");
            }
        }
    }

} 