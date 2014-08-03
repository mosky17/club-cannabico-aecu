<?php

require_once(dirname(__FILE__) . '/auth.php');

Auth::connect();

class Pago
{

    public $fecha_pago;
    public $id;
    public $id_socio;
    public $razon;
    public $valor;
    public $tipo;
    public $notas;
    public $cancelado;

    function __construct($id, $id_socio, $fecha_pago, $razon, $valor, $tipo, $notas, $cancelado)
    {

        $this->id = $id;
        $this->id_socio = $id_socio;
        $this->fecha_pago = $fecha_pago;
        $this->razon = $razon;
        $this->valor = $valor;
        $this->tipo = $tipo;
        $this->notas = $notas;
        $this->cancelado = $cancelado;
    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $instance = new Pago($row['id'], $row['id_socio'], $row['fecha_pago'], $row['razon'], $row['valor'], $row['tipo'], $row['notas'], $row['cancelado']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_pago($id)
    {
        $q = mysql_query("SELECT * FROM pagos WHERE id=".$id);
        $result = Pago::mysql_to_instances($q);
        if (count($result) == 1) {
            return $result[0];
        } else {
            return array("error" => "Pago no encontrado");
        }
    }

    static public function cancelar_pago($id)
    {
        $q = mysql_query("UPDATE pagos SET cancelado=1 WHERE id=".$id);
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Pago no cancelado");
        }
    }

    static public function get_pagos_socio($id_socio)
    {
        $q = mysql_query("SELECT * FROM pagos WHERE id_socio=".$id_socio." AND cancelado=0 ORDER BY fecha_pago;");
        return Pago::mysql_to_instances($q);
    }

    static public function get_lista_pagos()
    {
        $q = mysql_query("SELECT * FROM pagos WHERE cancelado=0 ORDER BY fecha_pago;");
        return Pago::mysql_to_instances($q);
    }

    static public function get_lista_pagos_con_cancelados()
    {
        $q = mysql_query("SELECT * FROM pagos ORDER BY fecha_pago;");
        return Pago::mysql_to_instances($q);
    }

    static public function ingresar_pago($id_socio,$valor,$fecha_pago,$razon,$tipo,$notas){

        $q = mysql_query("INSERT INTO pagos (id_socio, valor, fecha_pago, razon, tipo, notas) VALUES (" .
        htmlspecialchars(mysql_real_escape_string($id_socio)) . ", '" . htmlspecialchars(mysql_real_escape_string($valor)) . "', '" .
        htmlspecialchars(mysql_real_escape_string($fecha_pago)) . "', '" . htmlspecialchars(mysql_real_escape_string($razon)) . "', '" .
        htmlspecialchars(mysql_real_escape_string($tipo)) . "', '" . htmlspecialchars(mysql_real_escape_string($notas)) . "');");

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al insertar pago");
        }

    }

}
