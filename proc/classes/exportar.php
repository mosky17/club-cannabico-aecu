<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 02/08/13
 * Time: 09:52 PM
 * To change this template use File | Settings | File Templates.
 */

require_once(dirname(__FILE__) . '/auth.php');

Auth::connect();

class Exportar {

    static public function exportar_pagos_por_socio(){
        $fechaArchivo = date('Y-m-d');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=Lista_pagos_CSC-".$fechaArchivo.".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $array = array(array("Valor ($)", "Fecha pago", "Razon", "Via", "# de socio", "Nombre", "Email", "Notas" ));

        $pagos = Pago::get_lista_pagos();

        $result = mysql_query("SELECT * FROM pagos p, socios s WHERE p.id_socio = s.id AND p.cancelado=0 ORDER BY p.fecha_pago");

        //$array[] =  array($result, "SELECT * FROM pagos p, socios s WHERE p.id_socio = s.id AND p.cancelado=0 ORDER BY p.fecha_pago GROUP BY p.id");

        if($result){
            while ($row = mysql_fetch_array($result)) {
                $array[] = array(round($row['valor']), $row['fecha_pago'], $row['razon'], $row['tipo'], $row['numero'],
                    Exportar::sacarTildes($row['nombre']), $row['email'], '"'.Exportar::sacarTildes($row['notas']).'"');
            }
        }

        /*for($i = 0;$i<count($pagos);$i++){
            $array[] = array("$" . $pagos[$i]->valor, $pagos[$i]->fecha_pago, $pagos[$i]->razon, $pagos[$i]->tipo,
                $pagos[$i]->valor, $pagos[$i]->valor, $pagos[$i]->valor, $pagos[$i]->valor);
        }*/


        //$array = array(
        //    array("Valor", "Fecha pago", "Razón", "Via", "# de socio", "Nombre", "Email", "Notas" ),
         //   array("data21", "data22", "data23"),
         //   array("data31", "data32", "data23"));

        Exportar::outputCSV($array);
    }

    static public function exportar_pago_total_por_socio(){
        $fechaArchivo = date('Y-m-d');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=Total_pago_por_socio_CSC-".$fechaArchivo.".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $array = array(array("Cantidad ($)", "# de socio", "Nombre", "Email"));

        $result = mysql_query("SELECT * FROM pagos p, socios s WHERE p.id_socio = s.id AND p.cancelado=0 ORDER BY s.numero");
        $arrayPorSocio = array();

        if($result){
            while ($row = mysql_fetch_array($result)) {
                if($arrayPorSocio[$row['numero']]){
                    $arrayPorSocio[$row['numero']]['cantidad'] += round($row['valor']);
                }else{
                    $arrayPorSocio[$row['numero']] = array();
                    $arrayPorSocio[$row['numero']]['cantidad'] = round($row['valor']);
                    $arrayPorSocio[$row['numero']]['nombre'] = Exportar::sacarTildes($row['nombre']);
                    $arrayPorSocio[$row['numero']]['email'] = $row['email'];
                }
            }
        }

        foreach ($arrayPorSocio as $clave => $valor){
            $array[] = array($valor['cantidad'], $clave, $valor['nombre'], $valor['email']);
        }

        Exportar::outputCSV($array);
    }

    static public function exportar_caja(){
        $fechaArchivo = date('Y-m-d');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=Caja_CSC-".$fechaArchivo.".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $array = array(array("Fecha", "Concepto", "# de socio", "Nombre", "Debe ($)", "Haber ($)","Saldo ($)", "Notas" ));

        $gastos = Gasto::get_lista_gastos();
        $resultPagos = mysql_query("SELECT * FROM pagos p, socios s WHERE p.id_socio = s.id AND p.cancelado=0 ORDER BY p.fecha_pago");

        $indexGastos = 0;
        $rowResultPagos = mysql_fetch_array($resultPagos);
        $saldo = 0;

        while($indexGastos < count($gastos) || $rowResultPagos){
            if($rowResultPagos && $indexGastos < count($gastos)){
                if(strcmp($rowResultPagos['fecha_pago'],$gastos[$indexGastos]->fecha_pago) > 0){
                    $saldo -= round($gastos[$indexGastos]->valor);
                    if($gastos[$indexGastos]->valor>0){
                        //gasto
                        $array[] = array($gastos[$indexGastos]->fecha_pago, Exportar::sacarTildes($gastos[$indexGastos]->razon), "", "",
                            "", round($gastos[$indexGastos]->valor), $saldo , '"'.Exportar::sacarTildes($gastos[$indexGastos]->notas).'"');
                    }else{
                        //haber
                        $array[] = array($gastos[$indexGastos]->fecha_pago, Exportar::sacarTildes($gastos[$indexGastos]->razon), "", "",
                            round($gastos[$indexGastos]->valor*-1), "", $saldo , '"'.Exportar::sacarTildes($gastos[$indexGastos]->notas).'"');
                    }

                    $indexGastos += 1;
                }else{
                    $saldo += round($rowResultPagos['valor']);
                    $array[] = array($rowResultPagos['fecha_pago'], Exportar::sacarTildes($rowResultPagos['razon']), $rowResultPagos['numero'],
                        Exportar::sacarTildes($rowResultPagos['nombre']), round($rowResultPagos['valor']), "", $saldo , '"'.Exportar::sacarTildes($rowResultPagos['notas']).'"');
                    $rowResultPagos = mysql_fetch_array($resultPagos);
                }
            }elseif($rowResultPagos){
                $saldo += round($rowResultPagos['valor']);
                $array[] = array($rowResultPagos['fecha_pago'], Exportar::sacarTildes($rowResultPagos['razon']), $rowResultPagos['numero'],
                    Exportar::sacarTildes($rowResultPagos['nombre']), round($rowResultPagos['valor']), "", $saldo , '"'.Exportar::sacarTildes($rowResultPagos['notas']).'"');
                $rowResultPagos = mysql_fetch_array($resultPagos);
            }elseif($indexGastos < count($gastos)){
                $saldo -= round($gastos[$indexGastos]->valor);
                if($gastos[$indexGastos]->valor>0){
                    //gasto
                    $array[] = array($gastos[$indexGastos]->fecha_pago, Exportar::sacarTildes($gastos[$indexGastos]->razon), "", "",
                        "", round($gastos[$indexGastos]->valor), $saldo , '"'.Exportar::sacarTildes($gastos[$indexGastos]->notas).'"');
                }else{
                    //haber
                    $array[] = array($gastos[$indexGastos]->fecha_pago, Exportar::sacarTildes($gastos[$indexGastos]->razon), "", "",
                        round($gastos[$indexGastos]->valor*-1), "", $saldo , '"'.Exportar::sacarTildes($gastos[$indexGastos]->notas).'"');
                }

                $indexGastos += 1;
            }
        }

        Exportar::outputCSV($array);
    }

    private static function outputCSV($data) {
        $outstream = fopen("php://output", "w");
        function __outputCSV(&$vals, $key, $filehandler) {
            fputcsv($filehandler, $vals, ';'); // add parameters if you want
        }
        array_walk($data, "__outputCSV", $outstream);
        fclose($outstream);
    }

    private static function sacarTildes($text) {

        $result = str_replace("á", "a", $text);
        $result = str_replace("é", "e", $result);
        $result = str_replace("í", "i", $result);
        $result = str_replace("ó", "o", $result);
        $result = str_replace("ú", "u", $result);
        //$result = str_replace("ñ", "", $result);
        return $result;

    }
}