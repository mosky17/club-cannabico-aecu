<?php
/**
 * Created by JetBrains PhpStorm.
 * User: Martin
 * Date: 02/08/13
 * Time: 09:52 PM
 * To change this template use File | Settings | File Templates.
 */

require_once(dirname(__FILE__) . '/auth.php');
require_once(dirname(__FILE__).'/../../config.php');

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

        $array = array(array("Fecha", "Concepto", "# de socio", "Nombre", "Debe ($)", "Haber ($)","Saldo ($)", "Notas", "Rubro"));

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
                            "", round($gastos[$indexGastos]->valor), $saldo , '"'.Exportar::sacarTildes($gastos[$indexGastos]->notas).'"', '"'.Exportar::sacarTildes($gastos[$indexGastos]->rubro).'"');
                    }else{
                        //haber
                        $array[] = array($gastos[$indexGastos]->fecha_pago, Exportar::sacarTildes($gastos[$indexGastos]->razon), "", "",
                            round($gastos[$indexGastos]->valor*-1), "", $saldo , '"'.Exportar::sacarTildes($gastos[$indexGastos]->notas).'"', '"'.Exportar::sacarTildes($gastos[$indexGastos]->rubro).'"');
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
                        "", round($gastos[$indexGastos]->valor), $saldo , '"'.Exportar::sacarTildes($gastos[$indexGastos]->notas).'"', '"'.Exportar::sacarTildes($gastos[$indexGastos]->rubro).'"');
                }else{
                    //haber
                    $array[] = array($gastos[$indexGastos]->fecha_pago, Exportar::sacarTildes($gastos[$indexGastos]->razon), "", "",
                        round($gastos[$indexGastos]->valor*-1), "", $saldo , '"'.Exportar::sacarTildes($gastos[$indexGastos]->notas).'"', '"'.Exportar::sacarTildes($gastos[$indexGastos]->rubro).'"');
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

    static public function exportar_pagos_por_mes(){
        $fechaArchivo = date('Y-m-d');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=Lista_pagos_por_mes_".$GLOBALS['short_name']."-".$fechaArchivo.".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $rowTitulo = array("# Socio", "Nombre", "Matricula");
        $allRows = array();
        $matriculasPorSocio = array();
        $mesesDisponibles = array();
        $pagosPorMes = array();
        $socios = array();

        $result = mysql_query("SELECT * FROM pagos p, socios s WHERE s.activo=1 AND p.id_socio = s.id AND p.cancelado=0 ORDER BY s.numero");

        if($result){
            while ($row = mysql_fetch_array($result)) {

                //add socio to array
                if(count($socios) == 0 || $socios[count($socios)-1]["numero"] < $row['numero']){
                    $socios[] = array("id"=>$row['id'],"numero"=>$row['numero'],"nombre"=>$row['nombre']);
                }

                //es matricula
                if($row['razon'] == "matricula"){
                    if(array_key_exists($row["numero"],$matriculasPorSocio)){
                        $matriculasPorSocio[$row["numero"]] = $matriculasPorSocio[$row["numero"]] + round($row['valor']);
                    }else{
                        $matriculasPorSocio[$row["numero"]] = round($row['valor']);
                    }
                }
                //es mensualidad
                elseif(strpos($row['razon'],'mensualidad') == 0) {

                    $mes = substr($row['razon'],13, -1);

                    //add mes to array
                    if(!in_array($mes,$mesesDisponibles)){
                        $mesesDisponibles[] = $mes;
                    }

                    //add pago por mes
                    if(array_key_exists($row['numero'],$pagosPorMes)){
                        if(array_key_exists($mes,$pagosPorMes[$row['numero']])){
                            $pagosPorMes[$row['numero']][$mes] = $pagosPorMes[$row['numero']][$mes] + round($row['valor']);
                        }else{
                            $pagosPorMes[$row['numero']][$mes] = round($row['valor']);
                        }
                    }else{
                        $pagosPorMes[$row['numero']] = array($mes=>round($row['valor']));
                    }
                }
            }
        }

        //armar rows
        $allRows[] = array_merge($rowTitulo,$mesesDisponibles);

        foreach ($socios as $value) {
            $row = array($value["numero"],$value["nombre"],$matriculasPorSocio[$value["numero"]]);
            for($i = 0;$i<count($mesesDisponibles);$i++){
                if(array_key_exists($value["numero"],$pagosPorMes)){
                    if(array_key_exists($mesesDisponibles[$i],$pagosPorMes[$value["numero"]])){
                        $row[] = $pagosPorMes[$value["numero"]][$mesesDisponibles[$i]];
                    }else{
                        $row[] = "";
                    }
                }else{
                    $row[] = "";
                }
            }
            $allRows[] = $row;
        }

        Exportar::outputCSV($allRows);
    }

    static public function exportar_socios_activos(){
        $fechaArchivo = date('Y-m-d');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=ListaSociosActivos-".$fechaArchivo.".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $array = array(array("Numero", "Nombre", "Documento", "Fecha Nacimiento", "Fundador", "Fecha Ingreso", "Email", "Telefono"));

        $socios = Socio::get_socios_activos();

        if($socios){
            for($i = 0;$i< count($socios);$i++){
                $array[] = array($socios[$i]->numero,
                    $socios[$i]->nombre,
                    $socios[$i]->documento,
                    $socios[$i]->fecha_nacimiento,
                    "",
                    $socios[$i]->fecha_inicio,
                    $socios[$i]->email,
                    $socios[$i]->telefono);
            }
        }

        Exportar::outputCSV($array);
    }

    static public function exportar_deudas()
    {
        $fechaArchivo = date('Y-m-d');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=Lista_de_deudas_" . $GLOBALS['short_name'] . "-" . $fechaArchivo . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $rowTitulo = array("# Socio", "Nombre", "Total Adeudado");
        $allRows = array();
        $allRows[] = $rowTitulo;
        $adeudadoPorSocio = array();

        $socios = Socio::get_socios_activos();
        $sociosIndexed = array();
        $deudas = RecordatorioDeuda::GetAllDeudas();
        $total = 0;

        if($socios) {
            for ($i = 0; $i < count($socios); $i++) {
                $sociosIndexed[$socios[$i]->id] = $socios[$i];
            }
        }

        if($deudas) {
            for ($i = 0; $i < count($deudas); $i++) {
                if(!array_key_exists($deudas[$i]->id_socio,$adeudadoPorSocio)){
                    $adeudadoPorSocio[$deudas[$i]->id_socio] = $deudas[$i]->monto;
                }else{
                    $adeudadoPorSocio[$deudas[$i]->id_socio] = intval($adeudadoPorSocio[$deudas[$i]->id_socio]) + intval($deudas[$i]->monto);
                }
                $total += intval($deudas[$i]->monto);
            }
        }

        foreach ($adeudadoPorSocio as $clave => $valor){
            $allRows[] = array($sociosIndexed[$clave]->numero,$sociosIndexed[$clave]->nombre,$valor);
        }

        $allRows[] = array("","TOTAL",$total);

        Exportar::outputCSV($allRows);
    }

    static public function exportar_descuentos_por_socio()
    {
        $fechaArchivo = date('Y-m-d');
        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=Lista_de_descuentos_" . $GLOBALS['short_name'] . "-" . $fechaArchivo . ".csv");
        header("Pragma: no-cache");
        header("Expires: 0");

        $rowTitulo = array("# Socio", "Nombre", "Total");
        $allRows = array();
        $totalesPorSocio = array();
        $mesesDisponibles = array();
        $descuentosPorMes = array();
        $socios = array();

        $result = mysql_query("SELECT * FROM pagos p, socios s WHERE s.activo=1 AND p.id_socio = s.id AND p.cancelado=0 ORDER BY s.numero");

        if($result) {
            while ($row = mysql_fetch_array($result)) {

                if(strpos($row['razon'],'mensualidad') == 0) {

                    //add socio to array
                    if (count($socios) == 0 || $socios[count($socios) - 1]["numero"] < $row['numero']) {
                        $socios[] = array("id" => $row['id'], "numero" => $row['numero'], "nombre" => $row['nombre']);
                    }

                    if (array_key_exists($row["numero"], $totalesPorSocio)) {
                        $totalesPorSocio[$row["numero"]] = $totalesPorSocio[$row["numero"]] + round($row['descuento']);
                    } else {
                        $totalesPorSocio[$row["numero"]] = round($row['descuento']);
                    }

                    $mes = substr($row['razon'], 13, -1);

                    if(!empty($mes)) {

                        //add mes to array
                        if (!in_array($mes, $mesesDisponibles)) {
                            $mesesDisponibles[] = $mes;
                        }

                        //add descuento por mes
                        if (array_key_exists($row['numero'], $descuentosPorMes)) {
                            if (array_key_exists($mes, $descuentosPorMes[$row['numero']])) {
                                $descuentosPorMes[$row['numero']][$mes] = $descuentosPorMes[$row['numero']][$mes] + round($row['descuento']);
                            } else {
                                $descuentosPorMes[$row['numero']][$mes] = round($row['descuento']);
                            }
                        } else {
                            $descuentosPorMes[$row['numero']] = array($mes => round($row['descuento']));
                        }
                    }
                }
            }
        }

        //armar rows
        $allRows[] = array_merge($rowTitulo,$mesesDisponibles);

        foreach ($socios as $value) {
            $row = array($value["numero"],$value["nombre"],$totalesPorSocio[$value["numero"]]);
            for($i = 0;$i<count($mesesDisponibles);$i++){
                if(array_key_exists($value["numero"],$descuentosPorMes)){
                    if(array_key_exists($mesesDisponibles[$i],$descuentosPorMes[$value["numero"]])){
                        $row[] = $descuentosPorMes[$value["numero"]][$mesesDisponibles[$i]];
                    }else{
                        $row[] = "";
                    }
                }else{
                    $row[] = "";
                }
            }
            $allRows[] = $row;
        }

        Exportar::outputCSV($allRows);
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