<?php

require_once(dirname(__FILE__) . '/auth.php');

Auth::connect();

class RecordatorioDeuda {

    public $id;
    public $id_socio;
    public $monto;
    public $razon;

    function __construct($id, $id_socio, $monto, $razon)
    {

        $this->id = $id;
        $this->id_socio = $id_socio;
        $this->monto = $monto;
        $this->razon = $razon;
    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $instance = new RecordatorioDeuda($row['id'], $row['id_socio'], $row['monto'], $row['razon']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function GetDeudasSocio($id_socio)
    {
        $q = mysql_query("SELECT * FROM recordatorios_deuda WHERE id_socio=" . $id_socio . " ORDER BY id;");
        return RecordatorioDeuda::mysql_to_instances($q);
    }

    static public function GetAllDeudas(){

        $q = mysql_query("SELECT * FROM recordatorios_deuda");
        return RecordatorioDeuda::mysql_to_instances($q);
    }

    static public function IngresarDeuda($id_socio, $monto, $razon){

        $q = mysql_query("INSERT INTO recordatorios_deuda (id_socio, monto, razon) VALUES ('" .
            htmlspecialchars(mysql_real_escape_string($id_socio)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($monto)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($razon)) . "')");

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al insertar deuda");
        }

    }

    static public function CancelarDeuda($id){

        $q = mysql_query("DELETE FROM recordatorios_deuda WHERE id=" . $id);
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al cancelar deuda.");
        }
    }

} 