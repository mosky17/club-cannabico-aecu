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
    public $descuento;
    public $descuento_json;

    function __construct($id, $id_socio, $fecha_pago, $razon, $valor, $tipo, $notas, $cancelado, $descuento, $descuento_json)
    {

        $this->id = $id;
        $this->id_socio = $id_socio;
        $this->fecha_pago = $fecha_pago;
        $this->razon = $razon;
        $this->valor = $valor;
        $this->tipo = $tipo;
        $this->notas = $notas;
        $this->cancelado = $cancelado;
        $this->descuento = $descuento;
        $this->descuento_json = $descuento_json;
    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if ($result) {
            while ($row = mysql_fetch_array($result)) {
                $instance = new Pago($row['id'], $row['id_socio'], $row['fecha_pago'], $row['razon'], $row['valor'], $row['tipo'],
                    $row['notas'], $row['cancelado'], $row['descuento'], $row['descuento_json']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_pago($id)
    {
        $q = mysql_query("SELECT * FROM pagos WHERE id=" . $id);
        $result = Pago::mysql_to_instances($q);
        if (count($result) == 1) {
            return $result[0];
        } else {
            return array("error" => "Pago no encontrado");
        }
    }

    static public function cancelar_pago($id)
    {
        $pago = Pago::get_pago($id);
        if (Dato::verificar_movimiento_caja($pago->fecha_pago) !== true) {
            return array("error" => "Caja cerrada! No se pueden alterar movimientos de esta fecha.");
        }

        $q = mysql_query("UPDATE pagos SET cancelado=1 WHERE id=" . $id);
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Pago no cancelado");
        }
    }

    static public function salvar_pago_modificar($id,$razon,$descuento,$descuento_json)
    {
        $pago = Pago::get_pago($id);
        if (Dato::verificar_movimiento_caja($pago->fecha_pago) !== true) {
            return array("error" => "Caja cerrada! No se pueden alterar movimientos de esta fecha.");
        }

        $q = mysql_query("UPDATE pagos SET razon='".$razon."', descuento='".$descuento."', descuento_json='".$descuento_json."' WHERE id=" . $id);
        //echo "UPDATE pagos SET razon='".$razon."', descuento='".$descuento."', descuento_json='".$descuento_json."' WHERE id=" . $id;
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Pago no modificado");
        }
    }



    static public function get_pagos_socio($id_socio)
    {
        $q = mysql_query("SELECT * FROM pagos WHERE id_socio=" . $id_socio . " AND cancelado=0 ORDER BY fecha_pago DESC;");
        return Pago::mysql_to_instances($q);
    }

    static public function get_lista_pagos()
    {
        $q = mysql_query("SELECT * FROM pagos WHERE cancelado=0 ORDER BY fecha_pago DESC;");
        return Pago::mysql_to_instances($q);
    }

    static public function get_lista_pagos_con_cancelados()
    {
        $q = mysql_query("SELECT * FROM pagos ORDER BY fecha_pago;");
        return Pago::mysql_to_instances($q);
    }

    static public function ingresar_pago($id_socio, $valor, $fecha_pago, $razon, $tipo, $notas, $descuento, $descuento_json)
    {

        if (Dato::verificar_movimiento_caja($fecha_pago) !== true) {
            return array("error" => "Caja cerrada! No se pueden ingresar movimientos en esta fecha.");
        }

        $q = mysql_query("INSERT INTO pagos (id_socio, valor, fecha_pago, razon, descuento, descuento_json, tipo, notas) VALUES (" .
            htmlspecialchars(mysql_real_escape_string($id_socio)) . ", '" . htmlspecialchars(mysql_real_escape_string($valor)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($fecha_pago)) . "', '" . htmlspecialchars(mysql_real_escape_string($razon)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($descuento)) . "', '" . htmlspecialchars(mysql_real_escape_string($descuento_json)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($tipo)) . "', '" . htmlspecialchars(mysql_real_escape_string($notas)) . "');");

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al ingresar pago");
        }
    }

    static public function get_cuota_costos()
    {
        $result = mysql_query("SELECT * FROM cuota_costo;");
        $cuota_costos = array();
        if ($result) {
            while ($row = mysql_fetch_array($result)) {
                $cuota_costos[] = array(
                    "id" => $row['id'],
                    "valor" => $row['valor'],
                    "fecha_inicio" => $row['fecha_inicio'],
                    "fecha_fin" => $row['fecha_fin']
                );
            }
        }
        return $cuota_costos;
    }

    static public function delete_cuota_costo($id)
    {
        $q = mysql_query("DELETE FROM cuota_costo WHERE id=" . $id);
        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Registro no borrado");
        }
    }

    static public function ingresar_cuota_costo($valor, $fecha_inicio, $fecha_fin)
    {

        if (Dato::verificar_movimiento_caja($fecha_fin) !== true) {
            return array("error" => "Caja cerrada! No se pueden ingresar movimientos en esta fecha.");
        }

        $q = mysql_query("INSERT INTO cuota_costo (valor, fecha_inicio, fecha_fin) VALUES (" .
            htmlspecialchars(mysql_real_escape_string($valor)) . ", '" . htmlspecialchars(mysql_real_escape_string($fecha_inicio)) . "', '" .
            htmlspecialchars(mysql_real_escape_string($fecha_fin)) . "');");

        if (mysql_affected_rows() == 1) {
            return true;
        } else {
            return array("error" => "Error al ingresar registro de costo de cuota");
        }
    }

}
