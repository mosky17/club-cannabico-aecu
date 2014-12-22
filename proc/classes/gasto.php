<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 03/08/13
 * Time: 07:04 PM
 * To change this template use File | Settings | File Templates.
 */

require_once(dirname(__FILE__) . '/auth.php');
require_once(dirname(__FILE__) . '/pago.php');

Auth::connect();

class Gasto {

    public $fecha_pago;
    public $id;
    public $razon;
    public $valor;
    public $notas;
    public $cancelado;
    public $rubro;

    function __construct($id, $fecha_pago, $razon, $valor, $notas, $cancelado, $rubro)
    {

        $this->id = $id;
        $this->fecha_pago = $fecha_pago;
        $this->razon = $razon;
        $this->valor = $valor;
        $this->notas = $notas;
        $this->cancelado = $cancelado;
        $this->rubro = $rubro;
    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if($result){
            while ($row = mysql_fetch_array($result)) {
                $instance = new Gasto($row['id'], $row['fecha_pago'], $row['razon'], $row['valor'], $row['notas'], $row['cancelado'], $row['rubro']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_gasto($id)
    {
        $q = mysql_query("SELECT * FROM gastos WHERE id=".$id);
        $result = Gasto::mysql_to_instances($q);
        if (count($result) == 1) {
            return $result[0];
        } else {
            return array("error" => "Gasto no encontrado");
        }
    }

    static public function cancelar_gasto($id)
    {
        $q = mysql_query("UPDATE gastos SET cancelado=1 WHERE id=".$id);
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Gasto no cancelado");
        }
    }

    static public function update_rubro_gasto($id,$rubro)
    {
        $q = mysql_query("UPDATE gastos SET rubro='".$rubro."' WHERE id=".$id);
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Gasto no modificado");
        }
    }



    static public function get_lista_gastos()
    {
        $q = mysql_query("SELECT * FROM gastos WHERE cancelado=0 ORDER BY fecha_pago;");
        return Gasto::mysql_to_instances($q);
    }

    static public function get_lista_gastos_con_cancelados()
    {
        $q = mysql_query("SELECT * FROM gastos ORDER BY fecha_pago;");
        return Gasto::mysql_to_instances($q);
    }

    static public function ingresar_gasto($valor,$fecha_pago,$razon,$notas,$rubro){

        $q = mysql_query("INSERT INTO gastos (valor, fecha_pago, razon, notas, rubro) VALUES ('" . htmlspecialchars(mysql_real_escape_string($valor)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($fecha_pago)) . "', '" . htmlspecialchars(mysql_real_escape_string($razon)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($notas)) . "', '" . htmlspecialchars(mysql_real_escape_string($rubro)) . "');");

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al insertar gasto");
        }

    }
    static public function get_totales(){

        $retorno = array("ingresos_socios"=>0,
                         "otros_ingresos"=>0,
                         "gastos"=>0);

        $q = mysql_query("SELECT * FROM gastos WHERE cancelado=0");
        $gastos = Gasto::mysql_to_instances($q);

        for($i=0;$i<count($gastos);$i++){
            if($gastos[$i]->valor > 0){
                $retorno["gastos"] += $gastos[$i]->valor;
            }else{
                $retorno["otros_ingresos"] += $gastos[$i]->valor * -1;
            }
        }

        $pagos = Pago::get_lista_pagos();

        for($i=0;$i<count($pagos);$i++){
            $retorno["ingresos_socios"] += $pagos[$i]->valor;
        }

        return $retorno;
    }

}