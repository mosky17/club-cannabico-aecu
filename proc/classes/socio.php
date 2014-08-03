<?php

require_once(dirname(__FILE__) . '/auth.php');
require_once(dirname(__FILE__) . '/log.php');
require_once(dirname(__FILE__) . '/mandrill.php');

Auth::connect();

class Socio
{

    public $fecha_inicio;
    public $email;
    public $documento;
    public $numero;
    public $nombre;
    public $tags;
    public $id;
    public $telefono;
    public $observaciones;
    public $activo;

    function __construct($_id, $_nombre, $_fecha_inicio, $_email, $_documento, $_telefono, $_tags, $_numero, $_observaciones, $activo)
    {

        $this->id = $_id;
        $this->nombre = $_nombre;
        $this->fecha_inicio = $_fecha_inicio;
        $this->email = $_email;
        $this->documento = $_documento;
        $this->telefono = $_telefono;
        $this->tags = $_tags;
        $this->numero = $_numero;
        $this->observaciones = $_observaciones;
        $this->activo = $activo;

    }

    static private function mysql_to_instances($result)
    {
        $return = array();
        if ($result) {
            while ($row = mysql_fetch_array($result)) {
                $tags = explode(",", $row['tags']);

                $instance = new Socio($row['id'], $row['nombre'], $row['fecha_inicio'], $row['email'], $row['documento'], $row['telefono'], $tags, $row['numero'], $row['observaciones'], $row['activo']);
                $return[] = $instance;
            }
        }
        return $return;
    }

    static public function get_lista_socios()
    {
        $q = mysql_query("SELECT * FROM socios ORDER BY numero;");
        return Socio::mysql_to_instances($q);
    }

    static public function get_socios_activos()
    {
        $q = mysql_query("SELECT * FROM socios WHERE activo=1 ORDER BY numero;");
        return Socio::mysql_to_instances($q);
    }

    static public function get_socio($id)
    {
        $q = mysql_query("SELECT * FROM socios WHERE id = " . $id . ";");
        $result = Socio::mysql_to_instances($q);
        if (count($result) == 1) {
            return $result[0];
        } else {
            return array("error" => "Socio no encontrado");
        }
    }

    static public function get_tags()
    {
        $q = mysql_query("SELECT * FROM tags ORDER BY id;");
        $return = array();
        while ($row = mysql_fetch_array($q)) {
            $return[] = array("id" => $row['id'], "nombre" => $row['nombre'], "color" => $row['color']);
        }
        return $return;
    }

    static public function create_socio($numero, $nombre, $documento, $email, $fecha_inicio, $tags, $telefono, $observaciones)
    {

        //check number
        $q = mysql_query("SELECT * FROM socios WHERE numero=" . $numero);
        $sociosIgualNumero = Socio::mysql_to_instances($q);
        if ($sociosIgualNumero && count($sociosIgualNumero) > 0) {
            return array("error" => "Numero de socio ya existente");
        }

        $tagString = "";
        for ($i = 0; $i < count($tags); $i++) {
            $tagString .= $tags[$i] . ",";
        }
        $tagString = rtrim($tagString, ",");

        $q = mysql_query("INSERT INTO socios (id, numero, nombre, documento, email, fecha_inicio, tags, telefono, observaciones) VALUES (" .
        "null, " . htmlspecialchars(mysql_real_escape_string($numero)) . ", '" . htmlspecialchars(mysql_real_escape_string($nombre)) . "', '" .
        htmlspecialchars(mysql_real_escape_string($documento)) . "', '" . htmlspecialchars(mysql_real_escape_string($email)) . "', '" .
        htmlspecialchars(mysql_real_escape_string($fecha_inicio)) . "', '" . htmlspecialchars(mysql_real_escape_string($tagString)) . "', '" .
        htmlspecialchars(mysql_real_escape_string($telefono)) . "', '" . htmlspecialchars(mysql_real_escape_string($observaciones)) . "');");

        if (mysql_affected_rows() == 1) {
            return mysql_insert_id();
        } else {
            return array("error" => "Error al crear socio");
        }
    }

    static public function update_socio($id, $numero, $nombre, $documento, $email, $fecha_inicio, $tags, $telefono, $observaciones)
    {

        //check number
        $q = mysql_query("SELECT * FROM socios WHERE numero=" . $numero);
        $sociosIgualNumero = Socio::mysql_to_instances($q);
        if (count($sociosIgualNumero) > 1 || (count($sociosIgualNumero) == 1 && $sociosIgualNumero[0]->id != $id)) {
            return array("error" => "Numero de socio ya existente");
        }

        $tagString = "";
        for ($i = 0; $i < count($tags); $i++) {
            $tagString .= $tags[$i] . ",";
        }
        $tagString = rtrim($tagString, ",");

        $q = mysql_query("UPDATE socios SET numero=" . htmlspecialchars(mysql_real_escape_string($numero)) .
        ", nombre='" . htmlspecialchars(mysql_real_escape_string($nombre)) .
        "', documento='" . htmlspecialchars(mysql_real_escape_string($documento)) .
        "', email='" . htmlspecialchars(mysql_real_escape_string($email)) .
        "', fecha_inicio='" . htmlspecialchars(mysql_real_escape_string($fecha_inicio)) .
        "', tags='" . htmlspecialchars(mysql_real_escape_string($tagString)) .
        "', telefono='" . htmlspecialchars(mysql_real_escape_string($telefono)) .
        "', observaciones='" . htmlspecialchars(mysql_real_escape_string($observaciones)) . "' WHERE id=" . $id);

        if (mysql_affected_rows() == 1) {
            Log::log("Editar Socio", "Socio #" . $id . " " . $nombre . " editado");
            return $id;
        } else {
            return array("error" => "Error al editar socio");
        }
    }

    static public function importar_socio_aecu($numero)
    {
        Auth::connectAECU();
        $q = mysql_query("SELECT * FROM Personas p LEFT JOIN `Socios Pagos` sp ON p.Id=sp.Id_Persona WHERE sp.Numero_Socio=" . $numero);

        if (mysql_num_rows($q) == 1) {
            Auth::connect();
            $row = mysql_fetch_array($q);
            return Socio::create_socio($row['Numero_Socio'], $row['Nombre'], $row['Documento'], $row['Email'], date('Y-m-d'), "", $row['Telefono'], "");
        } elseif (mysql_num_rows($q) == 0) {
            Auth::connect();
            return array("error" => "Socio no encontrado");
        } else {
            Auth::connect();
            return array("error" => "CRITICO: Socio duplicado!");
        }
    }

    static public function update_estado_socio($id,$estado)
    {
        $q = mysql_query("UPDATE socios SET activo=" . $estado . " WHERE id=" . $id);

        if (mysql_affected_rows() == 1) {
            return true;
        }else{
            return array("error" => "Socio no actualizado");
        }
    }

    static public function eliminar_socio($id)
    {
        //check si no tiene pagos
        $q = mysql_query("SELECT * FROM pagos WHERE id_socio=" . $id . " AND cancelado=0");
        if(mysql_num_rows($q) > 0){
            return array("error" => "Imposible eliminar, socio tiene pagos a su nombre");
        }else{
            $q = mysql_query("DELETE FROM socios WHERE id=" . $id);
        }

        if (mysql_affected_rows() == 1) {
            return true;
        }else{
            return array("error" => "Socio no eliminado");
        }
    }

    static public function get_lista_mails($all,$tags)
    {
        if($all == 'true'){
            $q = mysql_query("SELECT * FROM socios WHERE activo=1");
        }else{

            $and = "";
            if($tags && count($tags) > 0){
                $and .= " AND (";
                for($i=0;$i<count($tags);$i++){
                    if($i > 0){
                        $and .= " OR ";
                    }
                    $and .= "tags like '" . $tags[$i] . ",%'";
                    $and .= " OR tags like '%," . $tags[$i] . ",%'";
                    $and .= " OR tags like '%," . $tags[$i] . "'";
                    $and .= " OR tags = '" . $tags[$i] . "'";
                }
                $and .= ")";
            }
            $q = mysql_query("SELECT * FROM socios WHERE activo=1" . $and);
        }

        //echo "SELECT * FROM socios WHERE cancelado=0" . $and;
        return Socio::mysql_to_instances($q);
    }

    static public function send_estados_de_cuenta($total,$texto){

        /*

        $socios = Socio::get_socios_activos();


        for($i=0;$i<count($socios);$i++){

            $htmlStyles = '<style type="text/css">.pagos-table{text-align: left;border-spacing:0;}' .
                '.pagos-table td{padding:5px 10px 5px 0;border:1px solid #EEE;}' .
                '.pagos-table th{padding:5px 10px 5px 0;border:1px solid #EEE;}' .
                '.titulo1{margin:0px;color:#888;}</style>';

            //texto inicial
            $htmlInicial = '<h2>Estado de cuenta de tu Club Social de Cannabis</h2><br>';
            $saltos = array("\r\n", "\n", "\r");
            $replace = '<br />';
            $newstr = str_replace($saltos, $replace, $texto);
            $htmlInicial .= '<p>'.$newstr.'</p>';

            $queryPagos = mysql_query("SELECT * FROM pagos WHERE id_socio = ".$socios[$i]->id." AND cancelado=0 ORDER BY fecha_pago");

            //armar total debe html
            $totalPago = 0;
            for($j=0;$j<mysql_num_rows($queryPagos);$j++){
                $totalPago += mysql_result($queryPagos,$j,'valor');
            }
            $htmlTotalDebido = "";
            if($totalPago < $total){
                $htmlTotalDebido = '<h4 style="color:red;">Tu saldo de cuenta es negativo, estas debiendo $'.($total-$totalPago).'</h4>';
            }elseif($totalPago > $total){
                $htmlTotalDebido = '<h4 style="color:green;">Tu saldo de cuenta es positivo, tienes $'.(($total-$totalPago)*-1).' a favor.</h4>';
            }else{
                $htmlTotalDebido = '<h4 style="color:green;">Tu cuenta esta al d&iacute;a.</h4>';
            }

            //lista pagos

            $htmlPagos = "<h4 class='titulo1'>Historial de Pagos</h4><table class='pagos-table'><tr><th>Fecha</th><th>Valor ($)</th><th>Raz&oacute;n</th><th>Via</th></tr>";
            for($j=0;$j<mysql_num_rows($queryPagos);$j++){

                $fecha = explode('-',mysql_result($queryPagos,$j,'fecha_pago'));
                $fecha = $fecha[2].'/'.$fecha[1].'/'.$fecha[0];

                $htmlPagos .= '<tr><td>'.$fecha.'</td>'.
                    '<td>'.mysql_result($queryPagos,$j,'valor').'</td>'.
                    '<td>'.mysql_result($queryPagos,$j,'razon').'</td>'.
                    '<td>'.mysql_result($queryPagos,$j,'tipo').'</td><tr>';
            }
            $htmlPagos .= '</table><br><br>';

            $htmlFirma = 'Cualquier duda o consulta nos responden este correo.<p style="margin:10px 0 0 0;">Saludos,</p><p style="margin:0;">Martin</p>';

            $htmlFinal = $htmlStyles . $htmlInicial . $htmlTotalDebido . $htmlPagos . $htmlFirma;

            $to = array(array("email"=>$socios[$i]->email));
            //$to = array(array("email"=>"martin.gaibisso@gmail.com"));
            Mandrill::SendDefault("",$htmlFinal,"Estado de cuenta de mi Club Social de Cannabis",$to,array("Estado de Cuenta CSC"));


        }

        */

    }

    public function has_tag($tag){
        if(strpos($this->tags, $tag) !== false){
            return true;
        }else{
            return false;
        }
    }
}
