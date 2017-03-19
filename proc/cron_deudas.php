<?php
/**
 * Copyright © 2015 ebizmarts. All rights reserved.
 * See LICENSE.txt for license details.
 */

require_once(dirname(__FILE__) . '/classes/mandrill.php');
require_once(dirname(__FILE__) . '/classes/socio.php');
require_once(dirname(__FILE__) . '/classes/pago.php');
require_once(dirname(__FILE__).'/../config.php');

date_default_timezone_set('America/Montevideo');

$MONTH_NAMES = array("01"=>'Enero',
    "02"=>'Febrero',
    "03"=>'Marzo',
    "04"=>'Abril',
    "05"=>'Mayo',
    "06"=>'Junio',
    "07"=>'Julio',
    "08"=>'Agosto',
    "09"=>'Setiembre',
    "10"=>'Octubre',
    "11"=>'Noviembre',
    "12"=>'Diciembre');
$current_month = date('m');
$current_year = date('Y');
$current_month_name = $MONTH_NAMES[$current_month];

$socios = Socio::get_socios_activos();

$tabla_socios = array();
$last_month = (int)$current_month - 1;
$last_month_year = $current_year;
if($last_month == 0){
    $last_month = "12";
    $last_month_year = (int)$last_month_year - 1;
}else{
    $last_month = strlen((string)$last_month) == 2 ? (string)$last_month : "0" . (string)$last_month;
}

$previous_month = (int)$last_month - 1;
$previous_month_year = $last_month_year;
if($previous_month == 0){
    $previous_month = "12";
    $previous_month_year = (int)$previous_month_year - 1;
}else{
    $previous_month = strlen((string)$previous_month) == 2 ? (string)$previous_month : "0" . (string)$previous_month;
}

//calcular costo cuota
//$cuota_costos = Pago::get_cuota_costos();
//for($i=0;$i<count($cuota_costos);$i++){
//    $mesInicio = explode("-",$cuota_costos[$i]["fecha_inicio"])[1];
//            var mesFin = Socio.CuotaCostos[i].fecha_fin.split("-")[1];
//            var yearInicio = Socio.CuotaCostos[i].fecha_inicio.split("-")[0];
//            var yearFin = Socio.CuotaCostos[i].fecha_fin.split("-")[0];
//
//            if(((yearInicio == year && mesInicio <= mes) || yearInicio < year) &&
//                ((yearFin == year && mesFin >= mes) || yearFin > year)){
//                $('#socioIngresarPagoValor').val(Socio.CuotaCostos[i].valor);
//                Socio.CurrentCostoCuota = Socio.CuotaCostos[i].valor;
//            }
//        }

$html_table = "<table><thead><tr><th>#</th><th>Nombre</th>".
    "<th>".$MONTH_NAMES[$previous_month]."/".$current_year."</th>".
    "<th>".$MONTH_NAMES[$last_month]."/".$last_month_year."</th>".
    "<th>".$MONTH_NAMES[$current_month]."/".$current_year."</th>".
    "</tr></thead><tbody>";

foreach($socios as $socio){

    $pagos_socio = Pago::get_pagos_socio($socio->id);
    $pago_current_month = 0;
    $descuento_current_month = 0;
    $pago_last_month = 0;
    $descuento_last_month = 0;
    $pago_previous_month = 0;
    $descuento_previous_month = 0;
    foreach($pagos_socio as $pago){

        if($pago->razon == "mensualidad (".$MONTH_NAMES[$current_month] . "/" . (string)$current_year . ")"){
            $pago_current_month = $pago_current_month + round($pago->valor);

            if($pago->descuento > 0){
                $descuento_current_month = $descuento_current_month + round($pago->descuento);
            }
        }
        if($pago->razon == "mensualidad (".$MONTH_NAMES[$last_month] . "/" . (string)$last_month_year . ")"){
            $pago_last_month = $pago_last_month + round($pago->valor);
            if($pago->descuento > 0){
                $descuento_last_month = $descuento_last_month + round($pago->descuento);
            }
        }
        if($pago->razon == "mensualidad (".$MONTH_NAMES[$previous_month] . "/" . (string)$previous_month_year . ")"){
            $pago_previous_month = $pago_previous_month + round($pago->valor);
            if($pago->descuento > 0){
                $descuento_previous_month = $descuento_previous_month + round($pago->descuento);
            }
        }
    }

    $descuento_string_current = "";
    $descuento_string_last = "";
    $descuento_string_previous = "";
    if($descuento_previous_month > 0){
        $descuento_string_previous = " (Descuento: $".$descuento_previous_month.")";
    }
    if($descuento_last_month > 0){
        $descuento_string_last = " (Descuento: $".$descuento_last_month.")";
    }
    if($descuento_current_month > 0){
        $descuento_string_current = " (Descuento: $".$descuento_current_month.")";
    }

    $html_table .= "<tr>".
        "<td>".$socio->numero."</td>".
        "<td>".$socio->nombre."</td>".
        "<td>$".$pago_previous_month . $descuento_string_previous . "</td>".
        "<td>$".$pago_last_month . $descuento_string_last . "</td>".
        "<td>$".$pago_current_month . $descuento_string_current . "</td></tr>";


}

$html_table .= "</tbody></table>";

$email_subject = "CCEP - Resumen de pagos";
$email_to = "martin.gaibisso@gmail.com";
$email_tags = array('CCEP');
echo Mandrill::SendDefault("",$html_table,$email_subject,$email_to,$email_tags);
